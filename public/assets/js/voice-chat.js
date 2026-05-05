// Voice Chat with Gemini Live API
// Using WebSocket for real-time bidirectional communication

class VoiceChat {
    constructor() {
        // Get API key from server (will be added to controller)
        this.apiKey = null;
        this.modelName = 'models/gemini-2.5-flash-native-audio-preview-12-2025';
        this.websocket = null;
        this.audioContext = null;
        this.mediaStream = null;
        this.audioWorklet = null;
        this.gainNode = null;
        this.isConnected = false;
        this.isRecording = false;
        
        // Audio playback queue
        this.audioQueue = [];
        this.isPlayingAudio = false;
        
        // Destinations data
        this.destinations = [];
        
        // UI Elements
        this.connectBtn = document.getElementById('connectBtn');
        this.disconnectBtn = document.getElementById('disconnectBtn');
        this.statusIndicator = document.getElementById('statusIndicator');
        this.statusText = document.getElementById('statusText');
        this.infoText = document.getElementById('infoText');
        this.conversationBox = document.getElementById('conversationBox');
        this.controlHint = document.getElementById('controlHint');
        this.audioVisualizer = document.getElementById('audioVisualizer');
        
        // Initialize visualizer bars
        this.initVisualizer();
        
        // Bind events
        this.connectBtn.addEventListener('click', () => this.connect());
        this.disconnectBtn.addEventListener('click', () => this.disconnect());
        
        // Fetch API key and destinations from server
        this.fetchApiKey();
        this.fetchDestinations();
    }
    
    async fetchDestinations() {
        try {
            const baseUrl = window.location.origin + window.location.pathname.split('/').slice(0, -2).join('/');
            const response = await fetch(baseUrl + '/chatbot/getDestinations');
            const data = await response.json();
            if (data.success && data.destinations) {
                this.destinations = data.destinations;
                console.log('✅ Loaded', data.total, 'destinations');
            } else {
                console.warn('⚠️ Failed to load destinations');
            }
        } catch (error) {
            console.error('Error fetching destinations:', error);
        }
    }
    
    async fetchApiKey() {
        try {
            // Extract base URL from current location
            const baseUrl = window.location.origin + window.location.pathname.split('/').slice(0, -2).join('/');
            const response = await fetch(baseUrl + '/chatbot/getVoiceApiKey');
            const data = await response.json();
            if (data.success && data.apiKey) {
                this.apiKey = data.apiKey;
                console.log('API Key loaded successfully');
            } else {
                this.showError('Failed to load API key. Please configure GEMINI_API_KEY in config/app.php');
            }
        } catch (error) {
            console.error('Error fetching API key:', error);
            this.showError('Failed to connect to server');
        }
    }
    
    initVisualizer() {
        // Create 20 visualizer bars
        for (let i = 0; i < 20; i++) {
            const bar = document.createElement('div');
            bar.className = 'visualizer-bar bg-gradient-to-t from-purple-500 to-pink-600 w-1 rounded-full transition-all duration-100';
            bar.style.height = '4px';
            this.audioVisualizer.appendChild(bar);
        }
    }
    
    animateVisualizer(isActive) {
        const bars = this.audioVisualizer.querySelectorAll('.visualizer-bar');
        if (isActive) {
            bars.forEach((bar, index) => {
                setInterval(() => {
                    const height = Math.random() * 48 + 4; // Random height between 4-52px
                    bar.style.height = `${height}px`;
                }, 100 + index * 20);
            });
        } else {
            bars.forEach(bar => {
                bar.style.height = '4px';
            });
        }
    }
    
    async connect() {
        if (!this.apiKey) {
            this.showError('API key not loaded. Please refresh the page.');
            return;
        }
        
        console.log('=== Starting Connection ===');
        console.log('API Key (first 10 chars):', this.apiKey.substring(0, 10) + '...');
        console.log('Model:', this.modelName);
        
        try {
            this.updateStatus('connecting', 'Connecting...', 'Menghubungkan ke server...');
            this.connectBtn.disabled = true;
            
            // List available audio devices
            const devices = await navigator.mediaDevices.enumerateDevices();
            const audioInputs = devices.filter(device => device.kind === 'audioinput');
            
            console.log('📱 Available microphones:');
            audioInputs.forEach((device, index) => {
                console.log(`  ${index + 1}. ${device.label || 'Microphone ' + (index + 1)} (${device.deviceId.substring(0, 10)}...)`);
            });
            
            // Request microphone permission with specific constraints
            console.log('Requesting microphone access...');
            this.mediaStream = await navigator.mediaDevices.getUserMedia({ 
                audio: {
                    channelCount: 1,
                    sampleRate: 16000,
                    echoCancellation: true,
                    noiseSuppression: true,
                    autoGainControl: true // Enable auto gain
                } 
            });
            
            // Check which device was selected
            const audioTrack = this.mediaStream.getAudioTracks()[0];
            const settings = audioTrack.getSettings();
            console.log('✅ Microphone selected:', audioTrack.label);
            console.log('📊 Audio settings:', settings);
            
            // Test audio level
            await this.testAudioLevel();
            
            console.log('Microphone access granted');
            
            // Initialize Audio Context
            this.audioContext = new (window.AudioContext || window.webkitAudioContext)({
                sampleRate: 16000
            });
            console.log('Audio context created, sample rate:', this.audioContext.sampleRate);
            
            // Connect to WebSocket
            const wsUrl = `wss://generativelanguage.googleapis.com/ws/google.ai.generativelanguage.v1beta.GenerativeService.BidiGenerateContent?key=${this.apiKey}`;
            console.log('Connecting to WebSocket...');
            console.log('WebSocket URL (without key):', wsUrl.split('?')[0]);
            
            this.websocket = new WebSocket(wsUrl);
            
            this.websocket.onopen = () => this.onWebSocketOpen();
            this.websocket.onmessage = (event) => this.onWebSocketMessage(event);
            this.websocket.onerror = (error) => this.onWebSocketError(error);
            this.websocket.onclose = (event) => this.onWebSocketClose(event);
            
        } catch (error) {
            console.error('Connection error:', error);
            console.error('Error name:', error.name);
            console.error('Error message:', error.message);
            
            if (error.name === 'NotAllowedError') {
                this.showError('Microphone access denied. Please allow microphone access in browser settings.');
            } else if (error.name === 'NotFoundError') {
                this.showError('No microphone found. Please connect a microphone.');
            } else {
                this.showError(`Failed to connect: ${error.message}`);
            }
            
            this.connectBtn.disabled = false;
            this.updateStatus('disconnected', 'Disconnected', 'Gagal terhubung');
        }
    }
    
    async testAudioLevel() {
        return new Promise((resolve) => {
            const testContext = new AudioContext({ sampleRate: 16000 });
            const source = testContext.createMediaStreamSource(this.mediaStream);
            const processor = testContext.createScriptProcessor(2048, 1, 1);
            
            let maxPeak = 0;
            let testCount = 0;
            
            processor.onaudioprocess = (e) => {
                const inputData = e.inputBuffer.getChannelData(0);
                let peak = 0;
                for (let i = 0; i < inputData.length; i++) {
                    const abs = Math.abs(inputData[i]);
                    if (abs > peak) peak = abs;
                }
                if (peak > maxPeak) maxPeak = peak;
                
                testCount++;
                if (testCount >= 5) { // Test for ~0.5 seconds
                    processor.disconnect();
                    source.disconnect();
                    testContext.close();
                    
                    console.log('🎤 Microphone test result:');
                    console.log('   Max peak:', maxPeak.toFixed(4));
                    
                    if (maxPeak < 0.001) {
                        console.warn('⚠️ WARNING: Microphone level is very low!');
                        console.warn('   Please check:');
                        console.warn('   1. Microphone is not muted');
                        console.warn('   2. Microphone volume is turned up');
                        console.warn('   3. Correct microphone is selected');
                        alert('⚠️ Microphone level is very low!\n\nPlease:\n1. Check microphone is not muted\n2. Increase microphone volume\n3. Try speaking louder\n\nClick OK to continue anyway.');
                    } else {
                        console.log('✅ Microphone level is good!');
                    }
                    
                    resolve();
                }
            };
            
            source.connect(processor);
            processor.connect(testContext.destination);
            
            console.log('🔍 Testing microphone level... (speak now)');
            this.addMessage('system', 'Testing microphone... Please speak!');
        });
    }
    
    onWebSocketOpen() {
        console.log('WebSocket connected');
        this.isConnected = true;
        
        // Check if destinations are loaded
        if (this.destinations.length === 0) {
            console.warn('⚠️ No destinations loaded! AI will not have destination data.');
            this.addMessage('system', 'Warning: Destination data not loaded');
        } else {
            console.log('✅ Loaded', this.destinations.length, 'destinations for function calling');
        }
        
        // Build system instruction - focus on behavior, not data
        const systemInstructionText = `Kamu adalah pemandu wisata Ambon yang ramah dan informatif.

ATURAN PENTING:
1. Gunakan function get_destination_info untuk mendapatkan informasi destinasi
2. JANGAN pernah menyebutkan destinasi yang tidak ada di database
3. Jika user tanya tentang destinasi, WAJIB panggil function dulu
4. Jawab singkat dan natural (1-2 kalimat)
5. Jika destinasi tidak ditemukan, katakan "Maaf, destinasi itu tidak ada di database kami"

Contoh:
User: "Apa kategori Pantai Natsepa?"
Kamu: [panggil get_destination_info dengan nama="Pantai Natsepa"]
Lalu jawab: "Pantai Natsepa termasuk kategori Pantai"`;

        // Define function declaration for getting destination info
        const tools = [{
            function_declarations: [{
                name: 'get_destination_info',
                description: 'Mendapatkan informasi lengkap tentang destinasi wisata di Ambon. Gunakan function ini setiap kali user bertanya tentang destinasi tertentu.',
                parameters: {
                    type: 'object',
                    properties: {
                        nama: {
                            type: 'string',
                            description: 'Nama destinasi yang ingin dicari (contoh: "Pantai Natsepa", "Benteng Victoria")'
                        }
                    },
                    required: ['nama']
                }
            }, {
                name: 'list_all_destinations',
                description: 'Mendapatkan daftar semua destinasi wisata yang tersedia di database. Gunakan ini jika user bertanya "destinasi apa saja" atau "ada destinasi apa".',
                parameters: {
                    type: 'object',
                    properties: {},
                    required: []
                }
            }, {
                name: 'search_by_category',
                description: 'Mencari destinasi berdasarkan kategori (Pantai, Sejarah, Religi, Kuliner)',
                parameters: {
                    type: 'object',
                    properties: {
                        kategori: {
                            type: 'string',
                            description: 'Kategori destinasi: Pantai, Sejarah, Religi, atau Kuliner',
                            enum: ['Pantai', 'Sejarah', 'Religi', 'Kuliner']
                        }
                    },
                    required: ['kategori']
                }
            }]
        }];

        // Send setup with tools
        const setupMessage = {
            setup: {
                model: this.modelName,
                generation_config: {
                    response_modalities: ['AUDIO'],
                    temperature: 0.2,
                    top_p: 0.7,
                    top_k: 40
                },
                system_instruction: {
                    parts: [{
                        text: systemInstructionText
                    }]
                },
                tools: tools
            }
        };
        
        console.log('📤 Sending setup with function calling');
        console.log('Tools:', JSON.stringify(tools, null, 2));
        this.websocket.send(JSON.stringify(setupMessage));
        console.log('✅ Setup sent');
        
        this.updateStatus('connected', 'Connected', 'Terhubung - Tunggu setup selesai...');
        this.disconnectBtn.disabled = false;
        this.animateVisualizer(true);
        
        this.addMessage('system', 'Koneksi berhasil! Menunggu setup...');
    }
    
    async startAudioCapture() {
        try {
            console.log('Starting ScriptProcessor audio capture...');
            await this.startScriptProcessorCapture();
        } catch (error) {
            console.error('Error starting audio capture:', error);
            this.showError('Failed to start audio capture');
        }
    }
    
    async startScriptProcessorCapture() {
        const source = this.audioContext.createMediaStreamSource(this.mediaStream);
        
        // Use smaller buffer size for lower latency (20-40ms chunks as per best practices)
        // 512 samples at 16kHz = 32ms (optimal)
        const bufferSize = 512;
        const processor = this.audioContext.createScriptProcessor(bufferSize, 1, 1);
        
        let audioChunkCount = 0;
        
        processor.onaudioprocess = (e) => {
            if (!this.isConnected || !this.websocket || this.websocket.readyState !== WebSocket.OPEN) {
                return;
            }
            
            const inputData = e.inputBuffer.getChannelData(0);
            
            // Calculate audio level for debugging
            let sum = 0;
            let max = 0;
            for (let i = 0; i < inputData.length; i++) {
                const abs = Math.abs(inputData[i]);
                sum += inputData[i] * inputData[i];
                if (abs > max) max = abs;
            }
            const rms = Math.sqrt(sum / inputData.length);
            const db = rms > 0 ? 20 * Math.log10(rms) : -100;
            
            // Log every 50th chunk (less spam)
            if (audioChunkCount % 50 === 0) {
                console.log(`🎙️ Audio #${audioChunkCount}, RMS: ${db.toFixed(1)} dB, Peak: ${max.toFixed(4)}`);
            }
            audioChunkCount++;
            
            // Convert Float32 to Int16 PCM
            const pcmData = new Int16Array(inputData.length);
            for (let i = 0; i < inputData.length; i++) {
                const s = Math.max(-1, Math.min(1, inputData[i]));
                pcmData[i] = s < 0 ? s * 0x8000 : s * 0x7FFF;
            }
            
            // Convert to base64
            const uint8Array = new Uint8Array(pcmData.buffer);
            let binary = '';
            for (let i = 0; i < uint8Array.length; i++) {
                binary += String.fromCharCode(uint8Array[i]);
            }
            const base64Audio = btoa(binary);
            
            // Send audio chunk - USE mediaChunks
            const audioMessage = {
                realtimeInput: {
                    mediaChunks: [{
                        mimeType: 'audio/pcm;rate=16000',
                        data: base64Audio
                    }]
                }
            };
            
            this.websocket.send(JSON.stringify(audioMessage));
        };
        
        // Connect source to processor and destination
        source.connect(processor);
        processor.connect(this.audioContext.destination);
        
        this.audioWorklet = processor;
        this.isRecording = true;
        
        console.log('✅ Audio capture started (32ms chunks for low latency)');
    }
    
    onWebSocketMessage(event) {
        try {
            // Check if data is Blob (binary data)
            if (event.data instanceof Blob) {
                console.log('Received Blob data, size:', event.data.size);
                // Convert Blob to text and parse
                event.data.text().then(text => {
                    try {
                        const response = JSON.parse(text);
                        this.processServerMessage(response);
                    } catch (error) {
                        console.error('Error parsing Blob as JSON:', error);
                        console.error('Blob text content:', text);
                    }
                });
                return;
            }
            
            // If it's already a string, parse it
            const response = JSON.parse(event.data);
            this.processServerMessage(response);
            
        } catch (error) {
            console.error('Error processing message:', error);
            console.error('Raw message type:', typeof event.data);
            console.error('Raw message:', event.data);
        }
    }
    
    processServerMessage(response) {
        console.log('Received message:', JSON.stringify(response, null, 2));
        
        // Handle setup complete
        if (response.setupComplete) {
            console.log('✅ Setup complete - ready for conversation');
            this.addMessage('system', 'Setup selesai! Siap berbicara.');
            this.updateStatus('connected', 'Connected', 'Terhubung - Mulai berbicara!');
            
            // Start audio capture immediately
            this.startAudioCapture();
        }
        
        // Handle server content (audio response and transcriptions)
        if (response.serverContent) {
            const content = response.serverContent;
            console.log('📦 Server content received');
            
            // Handle interruption - CRITICAL for smooth audio!
            if (content.interrupted) {
                console.log('⚠️ Generation interrupted - clearing audio queue');
                this.audioQueue = [];
                this.isPlayingAudio = false;
                // Stop any currently playing audio
                if (this.currentAudioSource) {
                    try {
                        this.currentAudioSource.stop();
                    } catch (e) {
                        // Already stopped
                    }
                    this.currentAudioSource = null;
                }
            }
            
            // Handle input transcription (what user said)
            if (content.inputTranscription && content.inputTranscription.text) {
                console.log('🎤 User said:', content.inputTranscription.text);
                this.addMessage('user', content.inputTranscription.text);
            }
            
            // Handle output transcription (what AI said)
            if (content.outputTranscription && content.outputTranscription.text) {
                console.log('🤖 AI said:', content.outputTranscription.text);
                this.addMessage('bot', content.outputTranscription.text);
            }
            
            // Handle audio response
            if (content.modelTurn && content.modelTurn.parts) {
                console.log('🔊 Model turn with', content.modelTurn.parts.length, 'parts');
                for (const part of content.modelTurn.parts) {
                    if (part.inlineData && part.inlineData.mimeType) {
                        console.log('🎵 Audio part:', part.inlineData.mimeType, 'size:', part.inlineData.data?.length || 0);
                        if (part.inlineData.mimeType.startsWith('audio/')) {
                            this.queueAudioChunk(part.inlineData.data, part.inlineData.mimeType);
                        }
                    }
                }
            }
            
            // Handle generation complete
            if (content.generationComplete) {
                console.log('✅ Generation complete');
            }
            
            // Handle turn complete
            if (content.turnComplete) {
                console.log('✅ Turn complete');
            }
        }
        
        // Handle session resumption updates
        if (response.sessionResumptionUpdate) {
            console.log('🔄 Session resumption update');
        }
        
        // Handle tool calls (if needed in future)
        if (response.toolCall) {
            console.log('🔧 Tool call received:', response.toolCall);
            this.handleToolCall(response.toolCall);
        }
        
        // Handle tool call cancellation
        if (response.toolCallCancellation) {
            console.log('❌ Tool call cancelled:', response.toolCallCancellation);
        }
        
        // Handle errors
        if (response.error) {
            console.error('❌ Server error:', response.error);
            this.showError(`Server error: ${response.error.message || 'Unknown error'}`);
            this.disconnect();
        }
    }
    
    handleToolCall(toolCall) {
        console.log('🔧 Processing tool call:', toolCall);
        
        const functionCalls = toolCall.functionCalls || [];
        const functionResponses = [];
        
        for (const call of functionCalls) {
            console.log('📞 Function:', call.name, 'Args:', call.args);
            
            let result = null;
            
            if (call.name === 'get_destination_info') {
                // Search for destination by name
                const searchName = call.args.nama.toLowerCase();
                const found = this.destinations.find(d => 
                    d.nama.toLowerCase().includes(searchName) || 
                    searchName.includes(d.nama.toLowerCase())
                );
                
                if (found) {
                    result = {
                        success: true,
                        data: {
                            nama: found.nama,
                            kategori: found.kategori,
                            lokasi: found.lokasi,
                            deskripsi: found.deskripsi,
                            jam_buka: found.jam_buka,
                            harga_tiket: found.harga_tiket,
                            fasilitas: found.fasilitas
                        }
                    };
                    console.log('✅ Found destination:', found.nama);
                } else {
                    result = {
                        success: false,
                        message: `Destinasi "${call.args.nama}" tidak ditemukan di database`
                    };
                    console.log('❌ Destination not found:', call.args.nama);
                }
                
            } else if (call.name === 'list_all_destinations') {
                // Return list of all destinations
                result = {
                    success: true,
                    total: this.destinations.length,
                    destinations: this.destinations.map(d => ({
                        nama: d.nama,
                        kategori: d.kategori,
                        lokasi: d.lokasi
                    }))
                };
                console.log('✅ Returning all', this.destinations.length, 'destinations');
                
            } else if (call.name === 'search_by_category') {
                // Search by category
                const kategori = call.args.kategori;
                const filtered = this.destinations.filter(d => 
                    d.kategori.toLowerCase() === kategori.toLowerCase()
                );
                
                result = {
                    success: true,
                    kategori: kategori,
                    total: filtered.length,
                    destinations: filtered.map(d => ({
                        nama: d.nama,
                        lokasi: d.lokasi,
                        deskripsi: d.deskripsi
                    }))
                };
                console.log('✅ Found', filtered.length, 'destinations in category:', kategori);
            }
            
            functionResponses.push({
                id: call.id,
                name: call.name,
                response: result
            });
        }
        
        // Send function responses back to AI
        const responseMessage = {
            toolResponse: {
                functionResponses: functionResponses
            }
        };
        
        console.log('📤 Sending tool response:', JSON.stringify(responseMessage, null, 2));
        this.websocket.send(JSON.stringify(responseMessage));
        console.log('✅ Tool response sent');
    }
    
    queueAudioChunk(base64Audio, mimeType) {
        // Add to queue
        this.audioQueue.push({ base64Audio, mimeType });
        
        // Start playing if not already playing
        if (!this.isPlayingAudio) {
            this.playNextAudioChunk();
        }
    }
    
    async playNextAudioChunk() {
        if (this.audioQueue.length === 0 || !this.audioContext) {
            this.isPlayingAudio = false;
            return;
        }
        
        this.isPlayingAudio = true;
        const { base64Audio, mimeType } = this.audioQueue.shift();
        
        try {
            // Decode base64 to binary
            const binaryString = atob(base64Audio);
            const bytes = new Uint8Array(binaryString.length);
            for (let i = 0; i < binaryString.length; i++) {
                bytes[i] = binaryString.charCodeAt(i);
            }
            
            // Convert to 16-bit PCM samples
            const samples = new Int16Array(bytes.buffer);
            
            // Convert to float32 for Web Audio API
            const floatSamples = new Float32Array(samples.length);
            for (let i = 0; i < samples.length; i++) {
                floatSamples[i] = samples[i] / 32768.0;
            }
            
            // Create audio buffer at 24kHz (Gemini output rate)
            const outputSampleRate = 24000;
            const audioBuffer = this.audioContext.createBuffer(1, floatSamples.length, outputSampleRate);
            audioBuffer.getChannelData(0).set(floatSamples);
            
            // Play audio
            const source = this.audioContext.createBufferSource();
            source.buffer = audioBuffer;
            source.connect(this.audioContext.destination);
            
            // Store reference for interruption handling
            this.currentAudioSource = source;
            
            // Play next chunk when this one ends
            source.onended = () => {
                this.currentAudioSource = null;
                this.playNextAudioChunk();
            };
            
            source.start(0);
            
        } catch (error) {
            console.error('Error playing audio chunk:', error);
            // Continue with next chunk even if this one fails
            this.playNextAudioChunk();
        }
    }
    
    onWebSocketError(error) {
        console.error('WebSocket error:', error);
        console.error('WebSocket state:', this.websocket?.readyState);
        console.error('API Key present:', !!this.apiKey);
        console.error('Model name:', this.modelName);
        this.showError('Connection error occurred. Check console for details.');
    }
    
    onWebSocketClose(event) {
        console.log('WebSocket closed');
        console.log('Close code:', event.code);
        console.log('Close reason:', event.reason);
        console.log('Was clean:', event.wasClean);
        
        this.isConnected = false;
        this.isRecording = false;
        this.updateStatus('disconnected', 'Disconnected', 'Koneksi terputus');
        this.connectBtn.disabled = false;
        this.disconnectBtn.disabled = true;
        this.animateVisualizer(false);
        
        // Show specific error message based on close code
        if (event.code === 1006) {
            this.addMessage('system', 'Koneksi terputus secara tidak normal. Periksa API key dan koneksi internet.');
        } else if (event.code === 1008) {
            this.addMessage('system', 'Koneksi ditolak. Periksa API key Anda.');
        } else if (event.code === 1000) {
            this.addMessage('system', 'Koneksi ditutup secara normal.');
        } else {
            this.addMessage('system', `Koneksi terputus (code: ${event.code}).`);
        }
    }
    
    disconnect() {
        if (this.websocket) {
            this.websocket.close();
        }
        
        // Stop current audio
        if (this.currentAudioSource) {
            try {
                this.currentAudioSource.stop();
            } catch (e) {
                // Already stopped
            }
            this.currentAudioSource = null;
        }
        
        // Clear audio queue
        this.audioQueue = [];
        this.isPlayingAudio = false;
        
        if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') {
            this.mediaRecorder.stop();
            this.mediaRecorder = null;
        }
        
        if (this.audioWorklet) {
            this.audioWorklet.disconnect();
            this.audioWorklet = null;
        }
        
        if (this.gainNode) {
            this.gainNode.disconnect();
            this.gainNode = null;
        }
        
        if (this.mediaStream) {
            this.mediaStream.getTracks().forEach(track => track.stop());
            this.mediaStream = null;
        }
        
        if (this.audioContext) {
            this.audioContext.close();
            this.audioContext = null;
        }
        
        this.isConnected = false;
        this.isRecording = false;
        this.updateStatus('disconnected', 'Disconnected', 'Terputus');
        this.connectBtn.disabled = false;
        this.disconnectBtn.disabled = true;
        this.animateVisualizer(false);
    }
    
    updateStatus(status, text, info) {
        this.statusText.textContent = text;
        this.infoText.textContent = info;
        
        // Update indicator color
        this.statusIndicator.classList.remove('bg-red-400', 'bg-yellow-400', 'bg-green-400');
        if (status === 'connected') {
            this.statusIndicator.classList.add('bg-green-400');
            this.controlHint.textContent = 'Berbicara dengan AI - Tekan tombol merah untuk mengakhiri';
        } else if (status === 'connecting') {
            this.statusIndicator.classList.add('bg-yellow-400');
            this.controlHint.textContent = 'Menghubungkan...';
        } else {
            this.statusIndicator.classList.add('bg-red-400');
            this.controlHint.textContent = 'Tekan tombol mikrofon untuk memulai percakapan';
        }
    }
    
    addMessage(type, text) {
        // Clear empty state if exists
        const emptyState = this.conversationBox.querySelector('.flex.flex-col.items-center');
        if (emptyState) {
            this.conversationBox.innerHTML = '';
        }
        
        const messageDiv = document.createElement('div');
        
        if (type === 'user') {
            messageDiv.className = 'flex justify-end';
            messageDiv.innerHTML = `
                <div class="bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-2xl rounded-tr-sm px-6 py-3 max-w-[70%]">
                    ${this.escapeHtml(text)}
                </div>
            `;
        } else if (type === 'bot') {
            messageDiv.className = 'flex justify-start';
            messageDiv.innerHTML = `
                <div class="bg-white border border-slate-200 rounded-2xl rounded-tl-sm px-6 py-3 max-w-[70%]">
                    ${this.escapeHtml(text)}
                </div>
            `;
        } else if (type === 'system') {
            messageDiv.className = 'flex justify-center';
            messageDiv.innerHTML = `
                <div class="bg-slate-200 text-slate-600 rounded-full px-4 py-2 text-sm">
                    ${this.escapeHtml(text)}
                </div>
            `;
        }
        
        this.conversationBox.appendChild(messageDiv);
        this.conversationBox.scrollTop = this.conversationBox.scrollHeight;
    }
    
    showError(message) {
        this.addMessage('system', `Error: ${message}`);
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    arrayBufferToBase64(buffer) {
        let binary = '';
        const bytes = new Uint8Array(buffer);
        const len = bytes.byteLength;
        for (let i = 0; i < len; i++) {
            binary += String.fromCharCode(bytes[i]);
        }
        return btoa(binary);
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    const voiceChat = new VoiceChat();
});
