<?php
/**
 * @var string $title Page title passed from controller
 */
?>
<!DOCTYPE html>
<html class="light" lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title><?= $title ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes pulse-ring {
            0% { transform: scale(0.95); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.7; }
            100% { transform: scale(0.95); opacity: 1; }
        }
        
        @keyframes wave {
            0%, 100% { transform: scaleY(0.5); }
            50% { transform: scaleY(1); }
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        
        .pulse-ring {
            animation: pulse-ring 2s ease-in-out infinite;
        }
        
        .wave-bar {
            animation: wave 1s ease-in-out infinite;
        }
        
        .fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        .slide-in-right {
            animation: slideInRight 0.4s ease-out forwards;
        }
        
        .slide-in-left {
            animation: slideInLeft 0.4s ease-out forwards;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .message-user {
            animation: slideInRight 0.4s ease-out;
        }
        
        .message-bot {
            animation: slideInLeft 0.4s ease-out;
        }
        
        /* Glowing effect for mic button */
        .mic-glow {
            box-shadow: 0 0 30px rgba(168, 85, 247, 0.6), 0 0 60px rgba(236, 72, 153, 0.4);
        }
        
        .mic-active {
            animation: pulse-ring 1.5s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-slate-50 via-purple-50 to-pink-50 text-slate-900 font-['Manrope'] min-h-screen flex flex-col">

<!-- Navigation -->
<nav class="fixed top-0 w-full z-50 bg-white/70 backdrop-blur-3xl shadow-sm">
    <div class="flex justify-between items-center w-full px-6 py-4 max-w-screen-2xl mx-auto">
        <a href="<?= BASE_URL ?>" class="flex items-center gap-2">
            <img src="<?= BASE_URL ?>public/assets/images/logo.png" alt="Ambon Oceanic Logo" class="h-10 w-auto">
        </a>
        <div class="hidden md:flex items-center gap-8 font-['Plus_Jakarta_Sans'] font-bold">
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>">Beranda</a>
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>destinasi">Destinasi</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>auth/favorites">Favorit</a>
            <?php endif; ?>
            <a class="text-sky-700 border-b-2 border-sky-700 pb-1" href="<?= BASE_URL ?>chatbot">AI Guide</a>
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>page/about">About</a>
            <a class="text-slate-600 hover:text-sky-600" href="<?= BASE_URL ?>page/contact">Contact</a>
        </div>
        <div class="flex items-center gap-4">
            <?php if (isset($_SESSION['user_id'])): ?>
                <span class="text-sm"><?= $_SESSION['user_name'] ?></span>
                <a href="<?= BASE_URL ?>auth/logout" class="text-slate-600 hover:text-sky-600">
                    <span class="material-symbols-outlined">logout</span>
                </a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>auth/login" class="bg-slate-100 hover:bg-slate-200 rounded-full px-6 py-2.5 font-bold text-sm">Sign In</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<main class="pt-32 pb-16 max-w-screen-xl mx-auto px-6">
    <div class="text-center mb-8 fade-in-up">
        <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 mb-6 float-animation shadow-2xl">
            <span class="material-symbols-outlined text-white text-5xl" style="font-variation-settings: 'FILL' 1;">mic</span>
        </div>
        <h1 class="font-['Plus_Jakarta_Sans'] text-5xl font-extrabold mb-3 bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Voice Chat AI</h1>
        <p class="text-slate-600 text-lg mb-2">Ngobrol langsung dengan Oceanic menggunakan suara Anda</p>
        <div class="inline-flex items-center gap-2 bg-purple-100 text-purple-700 px-4 py-2 rounded-full text-sm font-medium mb-4">
            <span class="material-symbols-outlined text-base">verified_user</span>
            <span>Selamat datang, <?= $_SESSION['user_name'] ?>!</span>
        </div>
        <br>
        <a href="<?= BASE_URL ?>chatbot" class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-700 mt-2 text-sm font-medium transition-all hover:gap-3">
            <span class="material-symbols-outlined text-base">arrow_back</span>
            Kembali ke Text Chat
        </a>
    </div>
    
    <div class="max-w-4xl mx-auto">
        <div class="bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden border border-white/20">
            <!-- Status Bar -->
            <div class="gradient-bg text-white px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div id="statusIndicator" class="w-3 h-3 rounded-full bg-red-400 pulse-ring"></div>
                        <span id="statusText" class="font-semibold text-lg">Disconnected</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm glass-effect px-4 py-2 rounded-full">
                        <span class="material-symbols-outlined text-base">info</span>
                        <span id="infoText">Klik tombol untuk mulai</span>
                    </div>
                </div>
            </div>
            
            <!-- Conversation Display -->
            <div id="conversationBox" class="h-[450px] overflow-y-auto p-8 space-y-4 bg-gradient-to-b from-slate-50/50 to-white/50">
                <div class="flex flex-col items-center justify-center h-full text-center">
                    <div class="relative mb-6">
                        <div class="absolute inset-0 bg-purple-200 rounded-full blur-3xl opacity-50 animate-pulse"></div>
                        <span class="material-symbols-outlined text-purple-300 text-8xl mb-4 relative float-animation" style="font-variation-settings: 'FILL' 1;">record_voice_over</span>
                    </div>
                    <p class="text-slate-500 font-semibold text-lg mb-2">Mulai percakapan dengan menekan tombol mikrofon</p>
                    <p class="text-slate-400 text-sm">Oceanic akan merespons dengan suara secara realtime</p>
                </div>
            </div>
            
            <!-- Audio Visualizer -->
            <div class="px-6 py-6 bg-gradient-to-r from-purple-50 to-pink-50 border-t border-purple-100">
                <div class="flex items-center justify-center gap-1.5 h-20" id="audioVisualizer">
                    <!-- Visualizer bars will be added here -->
                </div>
            </div>
            
            <!-- Controls -->
            <div class="p-8 bg-white/80 backdrop-blur-xl border-t border-slate-200">
                <div class="mb-6 flex justify-center">
                    <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-purple-100 flex items-center gap-3 transition-all hover:border-purple-300">
                        <span class="material-symbols-outlined text-purple-500">record_voice_over</span>
                        <select id="voiceSelect" class="bg-transparent border-none text-slate-700 font-medium focus:ring-0 cursor-pointer text-sm outline-none w-48">
                            <option value="Aoede">Suara 1 (Aoede - Wanita)</option>
                            <option value="Charon">Suara 2 (Charon - Pria)</option>
                            <option value="Fenrir">Suara 3 (Fenrir - Pria)</option>
                            <option value="Kore">Suara 4 (Kore - Wanita)</option>
                            <option value="Puck" selected>Suara 5 (Puck - Pria)</option>
                        </select>
                    </div>
                </div>
                <div class="flex items-center justify-center gap-6">
                    <button id="connectBtn" 
                            class="relative bg-gradient-to-br from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white rounded-full w-24 h-24 flex items-center justify-center shadow-2xl transition-all transform hover:scale-110 active:scale-95">
                        <div class="absolute inset-0 rounded-full bg-gradient-to-br from-purple-400 to-pink-500 blur-xl opacity-50"></div>
                        <span class="material-symbols-outlined text-5xl relative z-10">mic</span>
                    </button>
                    <button id="disconnectBtn" disabled
                            class="bg-slate-300 text-slate-500 rounded-full w-20 h-20 flex items-center justify-center transition-all disabled:opacity-50 hover:bg-red-500 hover:text-white enabled:shadow-lg transform hover:scale-105 active:scale-95">
                        <span class="material-symbols-outlined text-4xl">call_end</span>
                    </button>
                </div>
                <div class="text-center mt-6">
                    <p class="text-sm text-slate-600 font-medium" id="controlHint">Tekan tombol mikrofon untuk memulai percakapan</p>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="w-full bg-slate-100 border-t border-slate-200 mt-16">
    <div class="flex flex-col md:flex-row justify-between items-center px-8 py-6 gap-4 max-w-screen-2xl mx-auto">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-sky-900" style="font-variation-settings: 'FILL' 1;">water</span>
            <span class="font-['Plus_Jakarta_Sans'] font-bold text-lg text-sky-900">Ambon Oceanic</span>
        </div>
        <div class="text-sm text-slate-500">© <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</div>
    </div>
</footer>

<script src="<?= BASE_URL ?>public/assets/js/voice-chat-v2.js"></script>

</body>
</html>
