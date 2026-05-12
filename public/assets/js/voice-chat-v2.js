/**
 * Voice Chat Client - Server-to-Server Architecture
 * Connects to Node.js proxy server which handles Gemini Live API
 */

class VoiceChat {
    constructor() {
        // Server configuration
        this.serverUrl = 'ws://localhost:8080'; // Node.js server
        this.websocket = null;
        this.audioContext = null;
        this.mediaStream = null;
        this.audioWorklet = null;
        this.isConnected = false;
        this.isRecording = false;
        
        // Audio playback queue
        this.audioQueue = [];
        this.isPlayingAudio = false;
        this.currentAudioSource = null;
        
        // Visualizer state
        this.visualizerIntervals = [];
        this.isVisualizerActive = false;
        
        // UI Elements
        this.connectBtn = document.getElementById('connectBtn');
        this.disconnectBtn = document.getElementById('disconnectBtn');
        this.statusIndicator = document.getElementById('statusIndicator');
        this.statusText = document.getElementById('statusText');
        this.infoText = document.getElementById('infoText');
        this.conversationBox = document.getElementById('conversationBox');
        this.controlHint = document.getElementById('controlHint');
        this.audioVisualizer = document.getElementById('audioVisualizer');
        this.voiceSelect = document.getElementById('voiceSelect');
        
        // Initialize visualizer bars
        this.initVisualizer();
        
        // Bind events
        this.connectBtn.addEventListener('click', () => this.connect());
        this.disconnectBtn.addEventListener('click', () => this.disconnect());
    }
    
    initVisualizer() {
        // Create 24 visualizer bars with gradient colors
        const colors = [
            'from-purple-400 to-purple-600',
            'from-pink-400 to-pink-600',
            'from-purple-500 to-pink-500'
        ];
        
        for (let i = 0; i < 24; i++) {
            const bar = document.createElement('div');
            const colorClass = colors[i % 3];
            bar.className = `visualizer-bar bg-gradient-to-t ${colorClass} w-1.5 rounded-full transition-all duration-150 shadow-lg`;
            bar.style.height = '8px';
            this.audioVisualizer.appendChild(bar);
        }
    }
    
    startVisualizer() {
        if (this.isVisualizerActive) return;
        
        this.isVisualizerActive = true;
        const bars = this.audioVisualizer.querySelectorAll('.visualizer-bar');
        
        // Clear any existing intervals
        this.stopVisualizer();
        
        bars.forEach((bar, index) => {
            const interval = setInterval(() => {
                const height = Math.random() * 60 + 8;
                bar.style.height = `${height}px`;
            }, 120 + index * 15);
            this.visualizerIntervals.push(interval);
        });
    }
    
    stopVisualizer() {
        this.isVisualizerActive = false;
        
        // Clear all intervals
        this.visualizerIntervals.forEach(interval => clearInterval(interval));
        this.visualizerIntervals = [];
        
        // Reset bars to minimum height
        const bars = this.audioVisualizer.querySelectorAll('.visualizer-bar');
        bars.forEach(bar => {
            bar.style.height = '8px';
        });
    }
    
    animateVisualizer(isActive) {
        // Deprecated - kept for compatibility
        if (isActive) {
            this.startVisualizer();
        } else {
            this.stopVisualizer();
        }
    }
    
    async connect() {
        const selectedVoice = this.voiceSelect ? this.voiceSelect.value : 'Puck';
        const wsUrl = `${this.serverUrl}?voice=${selectedVoice}`;
        
        console.log('=== Connecting to Voice Server ===');
        console.log('Server URL:', wsUrl);
        console.log('Selected Voice:', selectedVoice);
        
        // Disable voice selection during call
        if (this.voiceSelect) this.voiceSelect.disabled = true;
        
        try {
            this.updateStatus('connecting', 'Connecting...', 'Menghubungkan ke server...');
            this.connectBtn.disabled = true;
            
            // Request microphone permission
            console.log('Requesting microphone access...');
            this.mediaStream = await navigator.mediaDevices.getUserMedia({ 
                audio: {
                    channelCount: 1,
                    sampleRate: 16000,
                    echoCancellation: true,
                    noiseSuppression: true,
                    autoGainControl: true
                } 
            });
            
            const audioTrack = this.mediaStream.getAudioTracks()[0];
            console.log('✅ Microphone:', audioTrack.label);
            
            // Initialize Audio Context
            this.audioContext = new (window.AudioContext || window.webkitAudioContext)({
                sampleRate: 16000
            });
            console.log('✅ Audio context created');
            
            // Connect to WebSocket server
            console.log('Connecting to WebSocket server...');
            this.websocket = new WebSocket(wsUrl);
            
            this.websocket.onopen = () => this.onWebSocketOpen();
            this.websocket.onmessage = (event) => this.onWebSocketMessage(event);
            this.websocket.onerror = (error) => this.onWebSocketError(error);
            this.websocket.onclose = (event) => this.onWebSocketClose(event);
            
        } catch (error) {
            console.error('Connection error:', error);
            
            if (error.name === 'NotAllowedError') {
                this.showError('Microphone access denied. Please allow microphone access.');
            } else if (error.name === 'NotFoundError') {
                this.showError('No microphone found. Please connect a microphone.');
            } else {
                this.showError(`Failed to connect: ${error.message}`);
            }
            
            this.connectBtn.disabled = false;
            if (this.voiceSelect) this.voiceSelect.disabled = false;
            this.updateStatus('disconnected', 'Disconnected', 'Gagal terhubung');
        }
    }
    
    onWebSocketOpen() {
        console.log('✅ WebSocket connected to server');
        this.isConnected = true;
        
        this.updateStatus('connected', 'Connected', 'Terhubung - Menunggu server siap...');
        this.disconnectBtn.disabled = false;
        // Don't start visualizer yet - wait for actual audio activity
    }
    
    onWebSocketMessage(event) {
        try {
            const message = JSON.parse(event.data);
            console.log('📨 Message from server:', message.type || 'raw');
            
            // Log destinations if present
            if (message.destinations) {
                console.log('🏖️ Destinations received:', message.destinations.length, message.destinations);
            }
            
            // Handle different message types
            if (message.type === 'ready') {
                this.handleReady(message);
            } else if (message.type === 'serverContent') {
                console.log('📦 Server content with destinations:', message.destinations?.length || 0);
                this.handleServerContent(message.content, message.destinations || []);
            } else if (message.type === 'error') {
                this.handleError(message);
            } else if (message.type === 'geminiDisconnected') {
                console.log('Gemini disconnected');
            } else if (message.setupComplete) {
                // Direct Gemini message
                console.log('✅ Setup complete');
            } else if (message.serverContent) {
                // Direct Gemini serverContent
                this.handleServerContent(message.serverContent, []);
            }
            
        } catch (error) {
            console.error('Error processing message:', error);
        }
    }
    
    handleServerContent(content, destinations = []) {
        console.log('📦 Server content received');
        console.log('🏖️ Destinations to display:', destinations.length, destinations);
        
        // Handle interruption
        if (content.interrupted) {
            console.log('⚠️ Generation interrupted - clearing audio queue');
            this.audioQueue = [];
            this.isPlayingAudio = false;
            this.stopVisualizer(); // Stop visualizer on interruption
            if (this.currentAudioSource) {
                try {
                    this.currentAudioSource.stop();
                } catch (e) {}
                this.currentAudioSource = null;
            }
        }
        
        // Handle input transcription (what user said)
        if (content.inputTranscription && content.inputTranscription.text) {
            console.log('🎤 User said:', content.inputTranscription.text);
            this.addMessage('user', content.inputTranscription.text);
            this.stopVisualizer(); // Stop when user finishes speaking
        }
        
        // Handle output transcription (what AI said)
        if (content.outputTranscription && content.outputTranscription.text) {
            console.log('🤖 AI said:', content.outputTranscription.text);
            console.log('🤖 Adding message with destinations:', destinations.length);
            this.addMessage('bot', content.outputTranscription.text, destinations);
        }
        
        // Handle audio response
        if (content.modelTurn && content.modelTurn.parts) {
            console.log('🔊 Model turn with', content.modelTurn.parts.length, 'parts');
            for (const part of content.modelTurn.parts) {
                if (part.inlineData && part.inlineData.mimeType) {
                    console.log('🎵 Audio part:', part.inlineData.mimeType);
                    if (part.inlineData.mimeType.startsWith('audio/')) {
                        this.startVisualizer(); // Start visualizer when AI speaks
                        this.queueAudioChunk(part.inlineData.data, part.inlineData.mimeType);
                    }
                }
            }
        }
        
        // Handle turn complete
        if (content.turnComplete) {
            console.log('✅ Turn complete');
            // Don't stop visualizer here - let it stop when audio finishes playing
        }
    }
    
    handleError(message) {
        console.error('❌ Server error:', message.message);
    }
    
    async startAudioCapture() {
        try {
            console.log('Starting audio capture...');
            
            const source = this.audioContext.createMediaStreamSource(this.mediaStream);
            const bufferSize = 512; // 32ms chunks at 16kHz
            const processor = this.audioContext.createScriptProcessor(bufferSize, 1, 1);
            
            let audioChunkCount = 0;
            let isSpeaking = false;
            const SPEECH_THRESHOLD = -50; // dB threshold for speech detection
            
            processor.onaudioprocess = (e) => {
                if (!this.isConnected || !this.websocket || this.websocket.readyState !== WebSocket.OPEN) {
                    return;
                }
                
                const inputData = e.inputBuffer.getChannelData(0);
                
                // Calculate audio level
                let sum = 0;
                for (let i = 0; i < inputData.length; i++) {
                    sum += inputData[i] * inputData[i];
                }
                const rms = Math.sqrt(sum / inputData.length);
                const db = rms > 0 ? 20 * Math.log10(rms) : -100;
                
                // Detect if user is speaking
                const wasSpeaking = isSpeaking;
                isSpeaking = db > SPEECH_THRESHOLD;
                
                // Start visualizer when user starts speaking
                if (isSpeaking && !wasSpeaking) {
                    this.startVisualizer();
                }
                
                // Log every 50th chunk
                if (audioChunkCount % 50 === 0) {
                    console.log(`🎙️ Audio #${audioChunkCount}, Level: ${db.toFixed(1)} dB, Speaking: ${isSpeaking}`);
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
                
                // Send to server in Gemini format
                this.websocket.send(JSON.stringify({
                    realtimeInput: {
                        mediaChunks: [{
                            mimeType: 'audio/pcm;rate=16000',
                            data: base64Audio
                        }]
                    }
                }));
            };
            
            source.connect(processor);
            processor.connect(this.audioContext.destination);
            
            this.audioWorklet = processor;
            this.isRecording = true;
            
            console.log('✅ Audio capture started');
            
        } catch (error) {
            console.error('Error starting audio capture:', error);
            this.showError('Failed to start audio capture');
        }
    }
    
    handleReady(message) {
        console.log('✅ Server ready!');
        console.log('   Destinations loaded:', message.destinationsCount);
        
        this.updateStatus('connected', 'Connected', 'Siap - Mulai berbicara!');
        
        // Start audio capture
        this.startAudioCapture();
    }
    
    queueAudioChunk(base64Audio, mimeType) {
        this.audioQueue.push({ base64Audio, mimeType });
        
        if (!this.isPlayingAudio) {
            this.playNextAudioChunk();
        }
    }
    
    async playNextAudioChunk() {
        if (this.audioQueue.length === 0 || !this.audioContext) {
            this.isPlayingAudio = false;
            this.stopVisualizer(); // Stop visualizer when no more audio to play
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
            
            // Determine sample rate from mimeType or default to 24kHz
            let sampleRate = 24000;
            if (mimeType.includes('rate=')) {
                const match = mimeType.match(/rate=(\d+)/);
                if (match) {
                    sampleRate = parseInt(match[1]);
                }
            }
            
            // Convert to 16-bit PCM samples
            const samples = new Int16Array(bytes.buffer);
            
            // Convert to float32
            const floatSamples = new Float32Array(samples.length);
            for (let i = 0; i < samples.length; i++) {
                floatSamples[i] = samples[i] / 32768.0;
            }
            
            // Create audio buffer
            const audioBuffer = this.audioContext.createBuffer(1, floatSamples.length, sampleRate);
            audioBuffer.getChannelData(0).set(floatSamples);
            
            // Play audio
            const source = this.audioContext.createBufferSource();
            source.buffer = audioBuffer;
            source.connect(this.audioContext.destination);
            
            this.currentAudioSource = source;
            
            source.onended = () => {
                this.currentAudioSource = null;
                this.playNextAudioChunk();
            };
            
            source.start(0);
            
        } catch (error) {
            console.error('Error playing audio chunk:', error);
            this.playNextAudioChunk();
        }
    }
    
    onWebSocketError(error) {
        console.error('WebSocket error:', error);
    }
    
    onWebSocketClose(event) {
        console.log('WebSocket closed');
        console.log('Close code:', event.code);
        console.log('Close reason:', event.reason);
        
        this.isConnected = false;
        this.isRecording = false;
        this.stopVisualizer(); // Stop visualizer on disconnect
        this.updateStatus('disconnected', 'Disconnected', 'Koneksi terputus');
        this.connectBtn.disabled = false;
        this.disconnectBtn.disabled = true;
        if (this.voiceSelect) this.voiceSelect.disabled = false;
    }
    
    disconnect() {
        if (this.websocket) {
            this.websocket.close();
        }
        
        // Stop visualizer
        this.stopVisualizer();
        
        // Stop audio
        if (this.currentAudioSource) {
            try {
                this.currentAudioSource.stop();
            } catch (e) {}
            this.currentAudioSource = null;
        }
        
        this.audioQueue = [];
        this.isPlayingAudio = false;
        
        if (this.audioWorklet) {
            this.audioWorklet.disconnect();
            this.audioWorklet = null;
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
        if (this.voiceSelect) this.voiceSelect.disabled = false;
        this.animateVisualizer(false);
    }
    
    updateStatus(status, text, info) {
        this.statusText.textContent = text;
        this.infoText.textContent = info;
        
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
    
    addMessage(type, text, destinations = []) {
        console.log('➕ Adding message:', type, 'with', destinations.length, 'destinations');
        
        const emptyState = this.conversationBox.querySelector('.flex.flex-col.items-center');
        if (emptyState) {
            this.conversationBox.innerHTML = '';
        }
        
        const messageDiv = document.createElement('div');
        
        if (type === 'user') {
            messageDiv.className = 'flex justify-end message-user';
            messageDiv.innerHTML = `
                <div class="bg-gradient-to-br from-purple-500 to-pink-600 text-white rounded-2xl rounded-tr-sm px-6 py-4 max-w-[70%] shadow-lg">
                    <p class="text-sm font-medium">${this.escapeHtml(text)}</p>
                </div>
            `;
        } else if (type === 'bot') {
            // Build destination cards HTML
            let destinationCards = '';
            if (destinations && destinations.length > 0) {
                console.log('🏖️ Building cards for', destinations.length, 'destinations');
                destinationCards = '<div class="mt-4 space-y-3">';
                
                destinations.forEach(dest => {
                    console.log('📍 Building card for:', dest.nama);
                    
                    // Get base URL - remove /chatbot/voice from path
                    const pathParts = window.location.pathname.split('/');
                    const baseIndex = pathParts.indexOf('chatbot');
                    const basePath = baseIndex > 0 ? pathParts.slice(0, baseIndex).join('/') : '';
                    const baseUrl = window.location.origin + basePath + '/';
                    
                    console.log('🔗 Base URL:', baseUrl);
                    
                    // Build image URL
                    let imageUrl = dest.gambar || 'public/assets/images/logo.png';
                    if (!imageUrl.startsWith('http')) {
                        imageUrl = baseUrl + imageUrl;
                    }
                    
                    console.log('🖼️ Image URL:', imageUrl);
                    
                    // Build detail URL
                    const detailUrl = baseUrl + 'destinasi/detail/' + dest.id;
                    
                    console.log('🔗 Detail URL:', detailUrl);
                    
                    destinationCards += `
                        <a href="${detailUrl}" 
                           class="block bg-gradient-to-br from-white to-purple-50 rounded-xl overflow-hidden border-2 border-purple-100 hover:border-purple-300 hover:shadow-xl transition-all cursor-pointer transform hover:scale-[1.02]">
                            <div class="flex gap-4">
                                <img src="${imageUrl}" 
                                     alt="${this.escapeHtml(dest.nama)}" 
                                     class="w-28 h-28 object-cover flex-shrink-0"
                                     onerror="this.src='${baseUrl}public/assets/images/logo.png'">
                                <div class="flex-1 py-3 pr-4 min-w-0">
                                    <h4 class="font-bold text-slate-900 text-base mb-2">${this.escapeHtml(dest.nama)}</h4>
                                    <p class="text-xs text-slate-600 flex items-center gap-1.5 mb-1">
                                        <span class="material-symbols-outlined text-sm">category</span>
                                        <span>${this.escapeHtml(dest.kategori)}</span>
                                    </p>
                                    <p class="text-xs text-slate-600 flex items-center gap-1.5 mb-1">
                                        <span class="material-symbols-outlined text-sm">location_on</span>
                                        <span class="truncate">${this.escapeHtml(dest.lokasi)}</span>
                                    </p>
                                    <div class="text-xs text-slate-600 flex items-start gap-1.5 mb-1">
                                        <span class="material-symbols-outlined text-sm mt-0.5">schedule</span>
                                        <span class="whitespace-pre-line">${this.escapeHtml(dest.jam_operasional || dest.jam_buka || 'Tidak tersedia')}</span>
                                    </div>
                                    <p class="text-xs text-purple-700 font-semibold flex items-center gap-1.5">
                                        <span class="material-symbols-outlined text-sm">payments</span>
                                        <span>Rp ${this.formatNumber(dest.harga_tiket_raw || 0)}</span>
                                    </p>
                                </div>
                            </div>
                        </a>
                    `;
                });
                
                destinationCards += '</div>';
                console.log('✅ Destination cards HTML built');
            }
            
            messageDiv.className = 'flex justify-start message-bot';
            messageDiv.innerHTML = `
                <div class="bg-white border-2 border-purple-100 rounded-2xl rounded-tl-sm px-6 py-4 max-w-[70%] shadow-lg">
                    <p class="text-sm text-slate-700">${this.escapeHtml(text)}</p>
                    ${destinationCards}
                </div>
            `;
        } else if (type === 'system') {
            messageDiv.className = 'flex justify-center fade-in-up';
            messageDiv.innerHTML = `
                <div class="bg-purple-100 text-purple-700 rounded-full px-5 py-2 text-xs font-medium">
                    ${this.escapeHtml(text)}
                </div>
            `;
        }
        
        this.conversationBox.appendChild(messageDiv);
        this.conversationBox.scrollTop = this.conversationBox.scrollHeight;
        
        console.log('✅ Message added to conversation box');
    }
    
    showError(message) {
        this.addMessage('system', `Error: ${message}`);
    }
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
    
    formatNumber(num) {
        return Number(num).toLocaleString('id-ID');
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    const voiceChat = new VoiceChat();
});
