# 🚀 Quick Start Guide

## Cara Menjalankan Voice Server

### 1. Pastikan .env sudah dikonfigurasi

File `.env` harus berisi:
```env
GEMINI_API_KEY=AIzaSy...your_key_here
PORT=8080
PHP_API_URL=http://localhost/ambon_oceanic
```

### 2. Jalankan Server

```bash
npm start
```

Anda akan melihat:
```
✅ Voice Server running on port 8080
📡 WebSocket endpoint: ws://localhost:8080
🏥 Health check: http://localhost:8080/health
🎤 Ready to accept voice connections!
```

### 3. Test di Browser

1. Buka: `http://localhost/ambon_oceanic/chatbot/voice`
2. Klik tombol mikrofon
3. Izinkan akses microphone
4. Mulai berbicara!

## Troubleshooting

### Server tidak start
- Pastikan Node.js sudah terinstall: `node --version`
- Pastikan dependencies terinstall: `npm install`
- Check port 8080 tidak digunakan aplikasi lain

### "Failed to load destinations"
- Pastikan XAMPP/PHP server berjalan
- Test endpoint: `http://localhost/ambon_oceanic/chatbot/getDestinations`
- Pastikan `PHP_API_URL` di `.env` benar

### Browser tidak bisa connect
- Pastikan server berjalan (lihat console log)
- Check firewall tidak block port 8080
- Pastikan URL di browser benar

### AI tidak merespons
- Check console log di browser (F12)
- Check console log di server
- Pastikan API key valid
- Pastikan microphone berfungsi

## Arsitektur

```
Browser (User)
    ↕️ WebSocket (ws://localhost:8080)
Node.js Proxy Server
    ↕️ WebSocket (wss://generativelanguage.googleapis.com)
Gemini Live API
    ↕️ HTTP (http://localhost/ambon_oceanic)
PHP Backend (Database)
```

## Keuntungan Arsitektur Ini

✅ **API Key Aman** - Tidak terekspos ke browser
✅ **Database Integration** - Server query database untuk context
✅ **Reliable** - Server inject data destinasi langsung ke system instruction
✅ **Scalable** - Bisa tambah rate limiting, logging, dll di server

## Logs

Server akan menampilkan log seperti:
```
🔌 Client connected: abc123
   IP: ::1
📚 Loading destinations for client abc123...
✅ Loaded 6 destinations from database
📝 System instruction built (1234 chars)
🔗 Connecting to Gemini Live API for client abc123...
✅ Connected to Gemini for client abc123
📤 Sending setup to Gemini for client abc123
✅ Setup complete for client abc123
```

## Stop Server

Tekan `Ctrl+C` di terminal untuk stop server dengan graceful shutdown.
