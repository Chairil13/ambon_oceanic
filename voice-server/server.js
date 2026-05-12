/**
 * Ambon Oceanic Voice Chat Server
 * WebSocket Proxy: Browser <-> Node.js Server <-> Gemini Live API
 * This keeps API key secure on server side
 */

import { WebSocketServer, WebSocket } from 'ws';
import dotenv from 'dotenv';
import http from 'http';

dotenv.config();

const PORT = process.env.PORT || 8080;
const GEMINI_API_KEY = process.env.GEMINI_API_KEY;
const PHP_API_URL = process.env.PHP_API_URL || 'http://localhost/ambon_oceanic';
const GEMINI_WS_URL = `wss://generativelanguage.googleapis.com/ws/google.ai.generativelanguage.v1beta.GenerativeService.BidiGenerateContent?key=${GEMINI_API_KEY}`;

if (!GEMINI_API_KEY) {
    console.error('❌ GEMINI_API_KEY not found in .env file');
    process.exit(1);
}

console.log('🚀 Ambon Oceanic Voice Server Starting...');
console.log('📡 PHP API URL:', PHP_API_URL);
console.log('🔑 API Key:', GEMINI_API_KEY.substring(0, 10) + '...');

// Create HTTP server for health check
const server = http.createServer((req, res) => {
    res.setHeader('Access-Control-Allow-Origin', '*');
    res.setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
    res.setHeader('Access-Control-Allow-Headers', 'Content-Type');

    if (req.method === 'OPTIONS') {
        res.writeHead(200);
        res.end();
        return;
    }

    if (req.url === '/health') {
        res.writeHead(200, { 'Content-Type': 'application/json' });
        res.end(JSON.stringify({
            status: 'ok',
            timestamp: new Date().toISOString(),
            apiKeyConfigured: !!GEMINI_API_KEY
        }));
    } else {
        res.writeHead(404);
        res.end('Not Found');
    }
});

// Create WebSocket server for client connections
const wss = new WebSocketServer({ server });

// Fetch destinations from PHP backend
async function fetchDestinations() {
    try {
        const fetch = (await import('node-fetch')).default;
        const response = await fetch(`${PHP_API_URL}/chatbot/getDestinations`);
        const data = await response.json();

        if (data.success && data.destinations) {
            console.log('✅ Loaded', data.total, 'destinations from database');
            return data.destinations;
        } else {
            console.warn('⚠️ Failed to load destinations from PHP backend');
            return [];
        }
    } catch (error) {
        console.error('❌ Error fetching destinations:', error.message);
        return [];
    }
}

// Build system instruction with destination data
function buildSystemInstruction(destinations) {
    let instruction = `Kamu adalah Oceanic, asisten virtual pemandu wisata Ambon yang ramah dan informatif. Kamu dibuat oleh Alin, Dede, Joiner, Devi untuk membantu wisatawan menemukan destinasi menarik di Ambon.

IDENTITAS KAMU:
- Nama: Oceanic
- Pembuat: Alin, Dede, Joiner, Devi
- Tugas: Membantu wisatawan menemukan dan mengenal destinasi wisata di Ambon

DESTINASI YANG TERSEDIA (${destinations.length} destinasi):
`;

    destinations.forEach((dest, index) => {
        instruction += `
${index + 1}. ${dest.nama}
   - Kategori: ${dest.kategori}
   - Lokasi: ${dest.lokasi}
   - Deskripsi: ${dest.deskripsi}
   - Jam Buka: ${dest.jam_buka}
   - Harga Tiket: ${dest.harga_tiket}
`;
    });

    instruction += `
ATURAN PENTING:
1. HANYA sebutkan destinasi yang ada di daftar di atas
2. JANGAN membuat atau menyebutkan destinasi lain yang tidak ada di daftar
3. Jika user bertanya tentang destinasi yang tidak ada, katakan "Maaf, destinasi itu tidak ada di database kami"
4. Berikan informasi yang akurat sesuai data di atas
5. Jawab dengan singkat dan natural (1-3 kalimat)
6. Gunakan bahasa Indonesia yang ramah dan informatif
7. Jika ditanya siapa kamu, jawab: "Saya Oceanic, asisten virtual yang dibuat oleh Alin, Dede, Joiner, Devi untuk membantu Anda menjelajahi wisata Ambon"

Contoh percakapan:
User: "Halo, siapa kamu?"
Kamu: "Halo! Saya Oceanic, asisten virtual yang dibuat oleh Alin, Dede, Joiner, Devi. Saya siap membantu Anda menemukan destinasi wisata menarik di Ambon"

User: "Apa kategori Pantai Natsepa?"
Kamu: "Pantai Natsepa termasuk kategori Pantai"

User: "Ada destinasi apa saja?"
Kamu: "Ada ${destinations.length} destinasi: ${destinations.slice(0, 3).map(d => d.nama).join(', ')}, dan lainnya"

User: "Ceritakan tentang Benteng Amsterdam"
Kamu: "Maaf, Benteng Amsterdam tidak ada di database kami. Yang ada adalah Benteng Victoria"
`;

    return instruction;
}

// Handle client connections
wss.on('connection', async (clientWs, req) => {
    const clientId = Math.random().toString(36).substring(7);
    console.log(`\n🔌 Client connected: ${clientId}`);
    console.log('   IP:', req.socket.remoteAddress);

    // Extract voice selection from URL
    let selectedVoice = 'Puck'; // default
    try {
        const reqUrl = new URL(req.url, `http://${req.headers.host || 'localhost'}`);
        if (reqUrl.searchParams.has('voice')) {
            selectedVoice = reqUrl.searchParams.get('voice');
        }
    } catch (e) {
        console.error('Error parsing URL:', e);
    }
    console.log('   Voice:', selectedVoice);

    let geminiWs = null;
    let destinations = [];
    let isGeminiReady = false;

    try {
        // Load destinations from database
        console.log(`📚 Loading destinations for client ${clientId}...`);
        destinations = await fetchDestinations();

        if (destinations.length === 0) {
            console.warn(`⚠️ No destinations loaded for client ${clientId}`);
        }

        // Build system instruction
        const systemInstruction = buildSystemInstruction(destinations);
        console.log(`📝 System instruction built (${systemInstruction.length} chars)`);

        // Connect to Gemini Live API via WebSocket
        console.log(`🔗 Connecting to Gemini Live API for client ${clientId}...`);
        geminiWs = new WebSocket(GEMINI_WS_URL);

        // Handle Gemini WebSocket open
        geminiWs.on('open', () => {
            console.log(`✅ Connected to Gemini for client ${clientId}`);

            // Send setup message to Gemini
            const setupMessage = {
                setup: {
                    model: 'models/gemini-2.5-flash-native-audio-preview-12-2025',
                    generation_config: {
                        response_modalities: ['AUDIO'],
                        speech_config: {
                            voice_config: {
                                prebuilt_voice_config: {
                                    voice_name: selectedVoice
                                }
                            }
                        },
                        temperature: 0.2,
                        top_p: 0.7,
                        top_k: 40
                    },
                    system_instruction: {
                        parts: [{
                            text: systemInstruction
                        }]
                    }
                }
            };

            console.log(`📤 Sending setup to Gemini for client ${clientId}`);
            geminiWs.send(JSON.stringify(setupMessage));
        });

        // Handle messages from Gemini
        geminiWs.on('message', (data) => {
            try {
                const message = JSON.parse(data.toString());

                // Log message type
                if (message.setupComplete) {
                    console.log(`✅ Setup complete for client ${clientId}`);
                    isGeminiReady = true;

                    // Notify client that server is ready
                    clientWs.send(JSON.stringify({
                        type: 'ready',
                        message: 'Server ready, destinations loaded',
                        destinationsCount: destinations.length
                    }));
                } else if (message.serverContent) {
                    // Log ALL serverContent for debugging
                    console.log(`📦 serverContent:`, JSON.stringify(message.serverContent, null, 2));
                    
                    // Only process if we have outputTranscription (the actual text response)
                    if (message.serverContent.outputTranscription && message.serverContent.outputTranscription.text) {
                        const responseText = message.serverContent.outputTranscription.text;
                        console.log(`🔍 AI Response: "${responseText}"`);
                        
                        // Check for mentioned destinations
                        let mentionedDestinations = [];
                        
                        destinations.forEach(dest => {
                            if (responseText.toLowerCase().includes(dest.nama.toLowerCase())) {
                                console.log(`✅ Found destination: ${dest.nama}`);
                                
                                // Parse harga_tiket to get raw number
                                let hargaRaw = 0;
                                if (dest.harga_tiket) {
                                    const match = dest.harga_tiket.match(/[\d.]+/g);
                                    if (match) {
                                        hargaRaw = parseInt(match.join('').replace(/\./g, ''));
                                    }
                                }
                                
                                mentionedDestinations.push({
                                    id: dest.id || dest.nama.toLowerCase().replace(/\s+/g, '-'),
                                    nama: dest.nama,
                                    kategori: dest.kategori,
                                    lokasi: dest.lokasi,
                                    jam_operasional: dest.jam_operasional || dest.jam_buka,
                                    jam_buka: dest.jam_buka,
                                    harga_tiket: dest.harga_tiket,
                                    harga_tiket_raw: hargaRaw,
                                    gambar: dest.gambar || 'public/assets/images/logo.png'
                                });
                            }
                        });
                        
                        if (mentionedDestinations.length > 0) {
                            console.log(`📍 Sending ${mentionedDestinations.length} destinations:`, mentionedDestinations.map(d => d.nama));
                        }
                        
                        // Forward server content to client with destinations
                        clientWs.send(JSON.stringify({
                            type: 'serverContent',
                            content: message.serverContent,
                            destinations: mentionedDestinations
                        }));
                    } else {
                        // No transcription yet, just forward without destinations
                        clientWs.send(JSON.stringify({
                            type: 'serverContent',
                            content: message.serverContent,
                            destinations: []
                        }));
                    }
                } else {
                    // Forward other messages as-is
                    clientWs.send(data.toString());
                }

            } catch (error) {
                console.error(`❌ Error processing Gemini message for ${clientId}:`, error.message);
            }
        });

        // Handle Gemini WebSocket errors
        geminiWs.on('error', (error) => {
            console.error(`❌ Gemini WebSocket error for ${clientId}:`, error.message);
            clientWs.send(JSON.stringify({
                type: 'error',
                message: 'Gemini connection error: ' + error.message
            }));
        });

        // Handle Gemini WebSocket close
        geminiWs.on('close', (code, reason) => {
            console.log(`🔌 Gemini disconnected for ${clientId} (code: ${code})`);
            isGeminiReady = false;

            // Notify client
            if (clientWs.readyState === WebSocket.OPEN) {
                clientWs.send(JSON.stringify({
                    type: 'geminiDisconnected',
                    code: code,
                    reason: reason.toString()
                }));
            }
        });

        // Handle messages from client
        clientWs.on('message', (data) => {
            try {
                const message = JSON.parse(data.toString());

                // Only forward if Gemini is ready
                if (!isGeminiReady) {
                    console.warn(`⚠️ Client ${clientId} sent message but Gemini not ready yet`);
                    return;
                }

                // Forward client messages to Gemini
                if (geminiWs && geminiWs.readyState === WebSocket.OPEN) {
                    geminiWs.send(data.toString());
                } else {
                    console.error(`❌ Cannot forward message: Gemini WebSocket not open for ${clientId}`);
                    clientWs.send(JSON.stringify({
                        type: 'error',
                        message: 'Gemini connection not ready'
                    }));
                }

            } catch (error) {
                console.error(`❌ Error processing client message for ${clientId}:`, error.message);
            }
        });

        // Handle client disconnect
        clientWs.on('close', () => {
            console.log(`🔌 Client disconnected: ${clientId}`);

            // Close Gemini connection
            if (geminiWs && geminiWs.readyState === WebSocket.OPEN) {
                geminiWs.close();
            }
        });

    } catch (error) {
        console.error(`❌ Error initializing session for ${clientId}:`, error.message);
        clientWs.send(JSON.stringify({
            type: 'error',
            message: 'Failed to initialize voice session: ' + error.message
        }));
        clientWs.close();
    }
});

// Start server
server.listen(PORT, () => {
    console.log(`\n✅ Voice Server running on port ${PORT}`);
    console.log(`📡 WebSocket endpoint: ws://localhost:${PORT}`);
    console.log(`🏥 Health check: http://localhost:${PORT}/health`);
    console.log(`\n🎤 Ready to accept voice connections!\n`);
});

// Handle graceful shutdown
process.on('SIGINT', () => {
    console.log('\n\n🛑 Shutting down server...');
    wss.clients.forEach(client => {
        client.close();
    });
    server.close(() => {
        console.log('✅ Server closed');
        process.exit(0);
    });
});
