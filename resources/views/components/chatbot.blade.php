<div id="aiChatWidget">
    <!-- Chat Button -->
    <button id="aiChatBtn" onclick="toggleAIChat()">
        <i class="ti ti-message-circle"></i>
    </button>

    <!-- Chat Panel -->
    <div id="aiChatPanel" class="hidden">
        <div class="ai-chat-header">
            <div class="ai-chat-title">
                <i class="ti ti-sparkles"></i>
                <div>
                    <strong>AI Assistant</strong>
                    <span>Cendrawasih Karsa Store</span>
                </div>
            </div>
            <div style="display: flex; gap: 8px; align-items: center;">
                <button onclick="clearAIChat()" class="ai-chat-close" title="Hapus Obrolan" style="font-size: 14px; display: flex; align-items: center; justify-content: center; background: none; border: none; padding: 4px; cursor: pointer;"><i class="ti ti-trash"></i></button>
                <button onclick="toggleAIChat()" class="ai-chat-close" style="display: flex; align-items: center; justify-content: center; background: none; border: none; padding: 4px; cursor: pointer;"><i class="ti ti-x"></i></button>
            </div>
        </div>
        
        <div class="ai-chat-body" id="aiChatMessages">
            <div class="ai-msg bot">
                Halo! Saya Asisten AI Cendrawasih Karsa. Ada yang bisa saya bantu terkait inventori, prediksi, atau laporan hari ini?
            </div>
        </div>
        
        <div class="ai-chat-footer">
            <form id="aiChatForm" onsubmit="sendAIMessage(event)">
                <input type="text" id="aiChatInput" placeholder="Tanya sesuatu..." autocomplete="off" required>
                <button type="submit" id="aiChatSend"><i class="ti ti-send"></i></button>
            </form>
        </div>
    </div>
</div>


<script>
    // Muat dan kembalikan status chat saat halaman dimuat
    document.addEventListener("DOMContentLoaded", () => {
        const panel = document.getElementById('aiChatPanel');
        const messagesBox = document.getElementById('aiChatMessages');
        
        // Kembalikan status buka/tutup
        const isOpen = localStorage.getItem('ai_chat_open');
        if (isOpen === 'true') {
            panel.classList.remove('hidden');
        } else {
            panel.classList.add('hidden');
        }

        // Kembalikan riwayat pesan
        const savedHistory = localStorage.getItem('ai_chat_history');
        if (savedHistory) {
            messagesBox.innerHTML = savedHistory;
        }
        
        scrollToBottom();
    });

    // Simpan riwayat chat ke localStorage (mengabaikan indikator mengetik)
    function saveChatHistory() {
        const messagesBox = document.getElementById('aiChatMessages');
        const clone = messagesBox.cloneNode(true);
        const typingIndicators = clone.querySelectorAll('.ai-typing');
        typingIndicators.forEach(el => el.remove());
        localStorage.setItem('ai_chat_history', clone.innerHTML);
    }

    function toggleAIChat() {
        const panel = document.getElementById('aiChatPanel');
        panel.classList.toggle('hidden');
        const isOpen = !panel.classList.contains('hidden');
        localStorage.setItem('ai_chat_open', isOpen);
        if (isOpen) {
            document.getElementById('aiChatInput').focus();
            scrollToBottom();
        }
    }

    // Bersihkan obrolan dan riwayat
    function clearAIChat() {
        if (confirm("Hapus semua riwayat obrolan AI?")) {
            const messagesBox = document.getElementById('aiChatMessages');
            messagesBox.innerHTML = `<div class="ai-msg bot">Halo! Saya Asisten AI Cendrawasih Karsa. Ada yang bisa saya bantu terkait inventori, prediksi, atau laporan hari ini?</div>`;
            localStorage.removeItem('ai_chat_history');
            scrollToBottom();
        }
    }

    async function sendAIMessage(e) {
        e.preventDefault();
        const input = document.getElementById('aiChatInput');
        const message = input.value.trim();
        if (!message) return;

        const messagesBox = document.getElementById('aiChatMessages');
        const sendBtn = document.getElementById('aiChatSend');

        // Tambah pesan user ke chat box
        messagesBox.innerHTML += `<div class="ai-msg user">${escapeHTML(message)}</div>`;
        input.value = '';
        input.disabled = true;
        sendBtn.disabled = true;
        scrollToBottom();
        saveChatHistory();

        // Tambah indikator mengetik
        const typingId = 'typing-' + Date.now();
        messagesBox.innerHTML += `<div class="ai-typing" id="${typingId}"><span></span><span></span><span></span></div>`;
        scrollToBottom();

        try {
            const response = await fetch("{{ route('chatbot.send') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ message: message })
            });

            const data = await response.json();
            document.getElementById(typingId).remove();
            
            if (response.ok) {
                // format line break sederhana
                const formattedReply = escapeHTML(data.reply).replace(/\n/g, '<br>');
                messagesBox.innerHTML += `<div class="ai-msg bot">${formattedReply}</div>`;
            } else {
                messagesBox.innerHTML += `<div class="ai-msg bot" style="color:#C1121F">Error: Gagal memproses pesan.</div>`;
            }

        } catch (error) {
            const typingEl = document.getElementById(typingId);
            if (typingEl) typingEl.remove();
            messagesBox.innerHTML += `<div class="ai-msg bot" style="color:#C1121F">Error: Koneksi terputus.</div>`;
        }

        input.disabled = false;
        sendBtn.disabled = false;
        input.focus();
        scrollToBottom();
        saveChatHistory();
    }

    function scrollToBottom() {
        const messagesBox = document.getElementById('aiChatMessages');
        messagesBox.scrollTop = messagesBox.scrollHeight;
    }

    function escapeHTML(str) {
        return str.replace(/[&<>'"]/g, 
            tag => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                "'": '&#39;',
                '"': '&quot;'
            }[tag] || tag)
        );
    }
</script>
