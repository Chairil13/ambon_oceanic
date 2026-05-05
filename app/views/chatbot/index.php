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
</head>
<body class="bg-slate-50 text-slate-900 font-['Manrope']">

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
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-sky-400 to-blue-600 mb-4">
            <span class="material-symbols-outlined text-white text-4xl" style="font-variation-settings: 'FILL' 1;">smart_toy</span>
        </div>
        <h1 class="font-['Plus_Jakarta_Sans'] text-4xl font-extrabold mb-2">AI Travel Guide</h1>
        <p class="text-slate-600">Tanyakan apapun tentang destinasi wisata di Ambon</p>
    </div>
    
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
            <!-- Header with Clear Button (for logged-in users) -->
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="flex justify-end px-6 pt-4">
                <button onclick="clearChatHistory()" id="clearBtn" class="text-slate-500 hover:text-red-600 transition-colors flex items-center gap-1 text-sm">
                    <span class="material-symbols-outlined text-base">delete</span>
                    <span>Hapus Riwayat</span>
                </button>
            </div>
            <?php endif; ?>
            
            <!-- Chat Box -->
            <div id="chatBox" class="h-[500px] overflow-y-auto p-6 space-y-4 bg-slate-50">
                <?php if (isset($history) && !empty($history)): ?>
                    <?php foreach (array_reverse($history) as $chat): ?>
                        <!-- User Message -->
                        <div class="flex justify-end group">
                            <div class="relative bg-gradient-to-r from-sky-500 to-blue-600 text-white rounded-2xl rounded-tr-sm px-6 py-3 max-w-[70%]">
                                <span class="message-text"><?= nl2br(htmlspecialchars($chat['message'])) ?></span>
                                <button onclick="editMessage(this)" class="absolute -left-10 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-200 hover:bg-slate-300 rounded-full p-2 text-slate-700">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </button>
                            </div>
                        </div>
                        <!-- Bot Response -->
                        <div class="flex justify-start">
                            <div class="bg-white border border-slate-200 rounded-2xl rounded-tl-sm px-6 py-3 max-w-[70%]">
                                <?= nl2br(htmlspecialchars($chat['response'])) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="flex flex-col items-center justify-center h-full text-center">
                        <span class="material-symbols-outlined text-slate-300 text-6xl mb-4" style="font-variation-settings: 'FILL' 1;">chat</span>
                        <p class="text-slate-400 font-medium">Mulai percakapan dengan mengetik pesan di bawah</p>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Input Form -->
            <div class="p-6 bg-white border-t border-slate-200">
                <form id="chatForm" class="flex gap-3">
                    <input type="text" id="messageInput" 
                           class="flex-grow bg-slate-50 border-slate-200 rounded-2xl px-6 py-4 focus:ring-2 focus:ring-sky-500 focus:border-transparent outline-none" 
                           placeholder="Ketik pesan Anda..." required/>
                    <button type="button" id="voiceChatBtn"
                       class="bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white rounded-2xl px-6 py-4 font-bold flex items-center gap-2 transition-all"
                       title="Voice Chat">
                        <span class="material-symbols-outlined">mic</span>
                    </button>
                    <button type="submit" id="sendBtn"
                            class="bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-600 hover:to-blue-700 text-white rounded-2xl px-8 py-4 font-bold flex items-center gap-2 transition-all">
                        <span class="material-symbols-outlined">send</span>
                        Kirim
                    </button>
                </form>
            </div>
        </div>
        
        <!-- Suggestions -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <button onclick="sendSuggestion('Rekomendasi pantai terbaik di Ambon')" 
                    class="bg-white hover:bg-slate-50 border border-slate-200 rounded-2xl p-4 text-left transition-colors">
                <span class="material-symbols-outlined text-sky-600 mb-2">beach_access</span>
                <p class="font-medium text-sm">Rekomendasi pantai terbaik</p>
            </button>
            <button onclick="sendSuggestion('Tempat wisata sejarah di Ambon')" 
                    class="bg-white hover:bg-slate-50 border border-slate-200 rounded-2xl p-4 text-left transition-colors">
                <span class="material-symbols-outlined text-amber-600 mb-2">castle</span>
                <p class="font-medium text-sm">Tempat wisata sejarah</p>
            </button>
            <button onclick="sendSuggestion('Kuliner khas Ambon yang wajib dicoba')" 
                    class="bg-white hover:bg-slate-50 border border-slate-200 rounded-2xl p-4 text-left transition-colors">
                <span class="material-symbols-outlined text-orange-600 mb-2">restaurant</span>
                <p class="font-medium text-sm">Kuliner khas Ambon</p>
            </button>
        </div>
    </div>
</main>

<footer class="w-full bg-slate-100 border-t border-slate-200 mt-16">
    <div class="flex flex-col md:flex-row justify-between items-center px-8 py-12 gap-6 max-w-screen-2xl mx-auto">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-sky-900" style="font-variation-settings: 'FILL' 1;">water</span>
            <span class="font-['Plus_Jakarta_Sans'] font-bold text-lg text-sky-900">Ambon Oceanic</span>
        </div>
        <div class="text-sm text-slate-500">© <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</div>
    </div>
</footer>

<script>
const chatBox = document.getElementById('chatBox');
const chatForm = document.getElementById('chatForm');
const messageInput = document.getElementById('messageInput');
const sendBtn = document.getElementById('sendBtn');
const voiceChatBtn = document.getElementById('voiceChatBtn');

// In-memory chat history for guest users (lost on refresh)
let guestChatHistory = [];

// Check if user is logged in
const isLoggedIn = <?= isset($_SESSION['user_id']) ? 'true' : 'false' ?>;

// Voice chat button handler
voiceChatBtn.addEventListener('click', function() {
    if (!isLoggedIn) {
        // Show info card instead of alert
        showLoginRequiredCard();
    } else {
        // Redirect to voice chat
        window.location.href = '<?= BASE_URL ?>chatbot/voice';
    }
});

// Show login required card
function showLoginRequiredCard() {
    // Create overlay
    const overlay = document.createElement('div');
    overlay.id = 'loginRequiredOverlay';
    overlay.className = 'fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4 animate-fadeIn';
    overlay.style.animation = 'fadeIn 0.3s ease-out';
    
    // Create card
    overlay.innerHTML = `
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 transform animate-slideUp" style="animation: slideUp 0.3s ease-out">
            <div class="text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 mb-6" style="aspect-ratio: 1/1;">
                    <span class="material-symbols-outlined text-white text-4xl" style="font-variation-settings: 'FILL' 1;">lock</span>
                </div>
                <h2 class="font-['Plus_Jakarta_Sans'] text-2xl font-bold mb-3 text-slate-900">Login Diperlukan</h2>
                <p class="text-slate-600 mb-6">Voice Chat adalah fitur premium yang hanya tersedia untuk pengguna yang sudah login.</p>
                
                <div class="bg-purple-50 border border-purple-200 rounded-2xl p-4 mb-6">
                    <div class="flex items-start gap-3 text-left">
                        <span class="material-symbols-outlined text-purple-600 mt-0.5">info</span>
                        <div class="text-sm text-purple-900">
                            <p class="font-semibold mb-1">Keuntungan Login:</p>
                            <ul class="space-y-1 text-purple-700">
                                <li>• Akses Voice Chat dengan AI</li>
                                <li>• Riwayat percakapan tersimpan</li>
                                <li>• Simpan destinasi favorit</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="flex gap-3">
                    <button onclick="closeLoginCard()" class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold py-3 px-6 rounded-xl transition-all">
                        Batal
                    </button>
                    <a href="<?= BASE_URL ?>auth/login" class="flex-1 bg-gradient-to-r from-purple-500 to-pink-600 hover:from-purple-600 hover:to-pink-700 text-white font-bold py-3 px-6 rounded-xl transition-all text-center">
                        Login Sekarang
                    </a>
                </div>
            </div>
        </div>
    `;
    
    // Add to body
    document.body.appendChild(overlay);
    
    // Close on overlay click
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closeLoginCard();
        }
    });
}

// Close login card
function closeLoginCard() {
    const overlay = document.getElementById('loginRequiredOverlay');
    if (overlay) {
        overlay.style.animation = 'fadeOut 0.3s ease-out';
        setTimeout(() => overlay.remove(), 300);
    }
}

// Add animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
    @keyframes slideUp {
        from { 
            opacity: 0;
            transform: translateY(20px);
        }
        to { 
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);

chatForm.addEventListener('submit', function(e) {
    e.preventDefault();
    const message = messageInput.value.trim();
    if (!message) return;
    
    sendMessage(message);
});

function sendMessage(message) {
    addMessage(message, 'user');
    messageInput.value = '';
    
    sendBtn.disabled = true;
    sendBtn.innerHTML = '<span class="material-symbols-outlined animate-spin">progress_activity</span> Mengirim...';
    
    // Prepare request payload
    const payload = {
        message: message
    };
    
    // For guest users, send in-memory history for context
    if (!isLoggedIn) {
        payload.guestHistory = guestChatHistory;
    }
    
    fetch('<?= BASE_URL ?>chatbot/send', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            addMessage(data.response, 'bot', data.destinations || []);
            
            // For guest users, save to in-memory history
            if (!isLoggedIn) {
                guestChatHistory.push({
                    message: message,
                    response: data.response
                });
                
                // Keep only last 20 conversations in memory
                if (guestChatHistory.length > 20) {
                    guestChatHistory = guestChatHistory.slice(-20);
                }
            }
        } else {
            addMessage('Maaf, terjadi kesalahan.', 'bot', []);
        }
    })
    .catch(err => {
        addMessage('Maaf, layanan tidak tersedia.', 'bot', []);
    })
    .finally(() => {
        sendBtn.disabled = false;
        sendBtn.innerHTML = '<span class="material-symbols-outlined">send</span> Kirim';
    });
}

function sendSuggestion(text) {
    messageInput.value = text;
    sendMessage(text);
}

function addMessage(text, sender, destinations = []) {
    const messageDiv = document.createElement('div');
    messageDiv.className = sender === 'user' ? 'flex justify-end group' : 'flex justify-start';
    
    if (sender === 'user') {
        messageDiv.innerHTML = `
            <div class="relative bg-gradient-to-r from-sky-500 to-blue-600 text-white rounded-2xl rounded-tr-sm px-6 py-3 max-w-[70%]">
                <span class="message-text">${escapeHtml(text)}</span>
                <button onclick="editMessage(this)" class="absolute -left-10 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-200 hover:bg-slate-300 rounded-full p-2 text-slate-700">
                    <span class="material-symbols-outlined text-sm">edit</span>
                </button>
            </div>
        `;
    } else {
        let destinationCards = '';
        
        // Add destination cards if any destinations are mentioned
        if (destinations && destinations.length > 0) {
            destinationCards = '<div class="mt-3 space-y-2">';
            destinations.forEach(dest => {
                const imageUrl = dest.gambar.startsWith('http') 
                    ? dest.gambar 
                    : '<?= BASE_URL ?>' + dest.gambar;
                
                destinationCards += `
                    <div class="bg-slate-50 rounded-xl overflow-hidden border border-slate-200 hover:shadow-md transition-shadow">
                        <div class="flex gap-3">
                            <img src="${imageUrl}" 
                                 alt="${escapeHtml(dest.nama)}" 
                                 class="w-24 h-24 object-cover flex-shrink-0"
                                 onerror="this.src='<?= BASE_URL ?>public/assets/images/logo.png'">
                            <div class="flex-1 py-2 pr-3 min-w-0">
                                <h4 class="font-bold text-slate-900 text-sm mb-1">${escapeHtml(dest.nama)}</h4>
                                <p class="text-xs text-slate-600 flex items-center gap-1 mb-0.5">
                                    <span class="material-symbols-outlined text-xs">category</span>
                                    <span>${escapeHtml(dest.kategori)}</span>
                                </p>
                                <p class="text-xs text-slate-600 flex items-center gap-1 mb-0.5">
                                    <span class="material-symbols-outlined text-xs">location_on</span>
                                    <span class="truncate">${escapeHtml(dest.lokasi)}</span>
                                </p>
                                <p class="text-xs text-slate-600 flex items-center gap-1 mb-0.5">
                                    <span class="material-symbols-outlined text-xs">schedule</span>
                                    <span>${escapeHtml(dest.jam_buka)}</span>
                                </p>
                                <p class="text-xs text-slate-600 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-xs">payments</span>
                                    <span>Rp ${Number(dest.harga_tiket).toLocaleString('id-ID')}</span>
                                </p>
                            </div>
                        </div>
                    </div>
                `;
            });
            destinationCards += '</div>';
        }
        
        messageDiv.innerHTML = `
            <div class="bg-white border border-slate-200 rounded-2xl rounded-tl-sm px-6 py-3 max-w-[70%]">
                <div>${escapeHtml(text)}</div>
                ${destinationCards}
            </div>
        `;
    }
    
    chatBox.appendChild(messageDiv);
    chatBox.scrollTop = chatBox.scrollHeight;
}

function editMessage(button) {
    const messageContainer = button.parentElement;
    const messageTextElement = messageContainer.querySelector('.message-text');
    const currentText = messageTextElement.textContent;
    
    // Store original text in data attribute
    messageContainer.setAttribute('data-original-text', currentText);
    
    // Replace message with input field
    messageContainer.innerHTML = `
        <div class="flex items-center gap-2 w-full">
            <input type="text" 
                   class="flex-grow bg-white/20 border border-white/30 rounded-xl px-4 py-2 text-white placeholder-white/70 focus:outline-none focus:ring-2 focus:ring-white/50" 
                   value="${currentText.replace(/"/g, '&quot;')}"
                   id="editInput"
                   autofocus />
            <button onclick="saveEdit(this)" class="bg-white/20 hover:bg-white/30 rounded-full p-2 transition-colors">
                <span class="material-symbols-outlined text-sm">check</span>
            </button>
            <button onclick="cancelEdit(this)" class="bg-white/20 hover:bg-white/30 rounded-full p-2 transition-colors">
                <span class="material-symbols-outlined text-sm">close</span>
            </button>
        </div>
    `;
    
    // Focus on input
    const input = document.getElementById('editInput');
    input.focus();
    input.select();
    
    // Handle Enter key
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            saveEdit(input.nextElementSibling);
        }
    });
    
    // Handle Escape key
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            cancelEdit(input.nextElementSibling.nextElementSibling);
        }
    });
}

function saveEdit(button) {
    const input = button.previousElementSibling;
    const newText = input.value.trim();
    
    if (!newText) {
        alert('Pesan tidak boleh kosong');
        return;
    }
    
    const messageContainer = button.closest('.relative');
    const parentDiv = messageContainer.parentElement;
    const nextBotMessage = parentDiv.nextElementSibling;
    
    // For guest users, remove from in-memory history
    if (!isLoggedIn && nextBotMessage) {
        const oldMessage = messageContainer.getAttribute('data-original-text');
        const oldResponse = nextBotMessage.querySelector('.bg-white').textContent.trim();
        
        // Find and remove from guestChatHistory
        guestChatHistory = guestChatHistory.filter(chat => 
            !(chat.message === oldMessage && chat.response === oldResponse)
        );
    }
    
    // Remove current user message and bot response
    if (nextBotMessage) {
        nextBotMessage.remove();
    }
    parentDiv.remove();
    
    // Send new message
    sendMessage(newText);
}

function cancelEdit(button) {
    const messageContainer = button.closest('.relative');
    const originalText = messageContainer.getAttribute('data-original-text');
    
    messageContainer.innerHTML = `
        <span class="message-text">${escapeHtml(originalText)}</span>
        <button onclick="editMessage(this)" class="absolute -left-10 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-200 hover:bg-slate-300 rounded-full p-2 text-slate-700">
            <span class="material-symbols-outlined text-sm">edit</span>
        </button>
    `;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML.replace(/\n/g, '<br>');
}

function clearChatHistory() {
    if (!confirm('Apakah Anda yakin ingin menghapus semua riwayat chat? Tindakan ini tidak dapat dibatalkan.')) {
        return;
    }
    
    const clearBtn = document.getElementById('clearBtn');
    const originalHtml = clearBtn.innerHTML;
    clearBtn.disabled = true;
    clearBtn.innerHTML = '<span class="material-symbols-outlined text-base animate-spin">progress_activity</span> Menghapus...';
    
    fetch('<?= BASE_URL ?>chatbot/clearHistory', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'}
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Clear chat box
            chatBox.innerHTML = `
                <div class="flex flex-col items-center justify-center h-full text-center">
                    <span class="material-symbols-outlined text-slate-300 text-6xl mb-4" style="font-variation-settings: 'FILL' 1;">chat</span>
                    <p class="text-slate-400 font-medium">Mulai percakapan dengan mengetik pesan di bawah</p>
                </div>
            `;
            
            // Show success message
            alert(data.message || 'Riwayat chat berhasil dihapus');
        } else {
            alert(data.message || 'Gagal menghapus riwayat chat');
        }
    })
    .catch(err => {
        alert('Terjadi kesalahan saat menghapus riwayat chat');
    })
    .finally(() => {
        clearBtn.disabled = false;
        clearBtn.innerHTML = originalHtml;
    });
}

chatBox.scrollTop = chatBox.scrollHeight;
</script>

</body>
</html>
