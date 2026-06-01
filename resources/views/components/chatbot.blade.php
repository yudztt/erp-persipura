<div id="aiChatWidget">
    <!-- Chat Button -->
    <button id="aiChatBtn" onclick="toggleAIChat()">
        <i class="ti ti-robot"></i>
    </button>

    <!-- Chat Panel -->
    <div id="aiChatPanel" class="hidden">
        <div class="ai-chat-header">
            <div class="ai-chat-title">
                <i class="ti ti-sparkles"></i>
                <div>
                    <strong>AI Assistant</strong>
                    <span>ERP Cendrawasih Karsa</span>
                </div>
            </div>
            <button onclick="toggleAIChat()" class="ai-chat-close"><i class="ti ti-x"></i></button>
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

<style>
/* Chatbot CSS */
#aiChatWidget { position: fixed; bottom: 24px; right: 24px; z-index: 9999; font-family: 'Inter', system-ui, sans-serif; }
#aiChatBtn { 
    width: 56px; height: 56px; border-radius: 50%; background: #C1121F; color: white;
    border: none; box-shadow: 0 4px 12px rgba(193,18,31,0.3); cursor: pointer;
    display: flex; align-items: center; justify-content: center; transition: 0.2s;
}
#aiChatBtn:hover { transform: scale(1.05); }
#aiChatBtn i { font-size: 28px; }

#aiChatPanel {
    position: absolute; bottom: 70px; right: 0; width: 340px; height: 480px;
    background: #fff; border-radius: 16px; box-shadow: 0 8px 30px rgba(0,0,0,0.12);
    display: flex; flex-direction: column; overflow: hidden; border: 1px solid #E2E8F0;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    transform-origin: bottom right;
}
#aiChatPanel.hidden { transform: scale(0.8); opacity: 0; pointer-events: none; }

.ai-chat-header {
    background: #0F172A; color: white; padding: 16px;
    display: flex; justify-content: space-between; align-items: center;
}
.ai-chat-title { display: flex; align-items: center; gap: 10px; }
.ai-chat-title i { font-size: 20px; color: #FCA5A5; }
.ai-chat-title div { display: flex; flex-direction: column; line-height: 1.2; }
.ai-chat-title strong { font-size: 14px; }
.ai-chat-title span { font-size: 11px; color: #94A3B8; }
.ai-chat-close { background: none; border: none; color: #94A3B8; cursor: pointer; font-size: 18px; padding: 4px; }
.ai-chat-close:hover { color: white; }

.ai-chat-body {
    flex: 1; padding: 16px; overflow-y: auto; background: #F8FAFC;
    display: flex; flex-direction: column; gap: 12px; font-size: 13px;
}
.ai-msg { max-width: 85%; padding: 10px 14px; border-radius: 12px; line-height: 1.4; word-wrap: break-word;}
.ai-msg.bot { background: white; color: #1E293B; border: 1px solid #E2E8F0; align-self: flex-start; border-bottom-left-radius: 4px; }
.ai-msg.user { background: #C1121F; color: white; align-self: flex-end; border-bottom-right-radius: 4px; }
.ai-typing { display: flex; gap: 4px; padding: 12px 14px; align-self: flex-start; background: white; border-radius: 12px; border: 1px solid #E2E8F0;}
.ai-typing span { width: 6px; height: 6px; background: #94A3B8; border-radius: 50%; animation: bounce 1.4s infinite ease-in-out both; }
.ai-typing span:nth-child(1) { animation-delay: -0.32s; }
.ai-typing span:nth-child(2) { animation-delay: -0.16s; }
@keyframes bounce { 0%, 80%, 100% { transform: scale(0); } 40% { transform: scale(1); } }

.ai-chat-footer { padding: 12px 16px; background: white; border-top: 1px solid #F1F5F9; }
#aiChatForm { display: flex; gap: 8px; margin: 0; }
#aiChatInput {
    flex: 1; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 20px;
    font-size: 13px; outline: none; transition: 0.2s;
}
#aiChatInput:focus { border-color: #C1121F; }
#aiChatSend {
    width: 38px; height: 38px; border-radius: 50%; background: #0F172A; color: white;
    border: none; cursor: pointer; display: flex; align-items: center; justify-content: center;
}
#aiChatSend:hover { background: #1E293B; }
#aiChatSend i { font-size: 16px; margin-left: -2px; margin-top: 1px;}
#aiChatSend:disabled { background: #94A3B8; cursor: not-allowed; }

@media (max-width: 480px) {
    #aiChatPanel {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        width: 100%; height: 100%; border-radius: 0; transform-origin: center;
    }
}
</style>

<script>
    function toggleAIChat() {
        const panel = document.getElementById('aiChatPanel');
        panel.classList.toggle('hidden');
        if (!panel.classList.contains('hidden')) {
            document.getElementById('aiChatInput').focus();
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

        // Append User Msg
        messagesBox.innerHTML += `<div class="ai-msg user">${escapeHTML(message)}</div>`;
        input.value = '';
        input.disabled = true;
        sendBtn.disabled = true;
        scrollToBottom();

        // Append Typing indicator
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
                // format simple line breaks
                const formattedReply = escapeHTML(data.reply).replace(/\n/g, '<br>');
                messagesBox.innerHTML += `<div class="ai-msg bot">${formattedReply}</div>`;
            } else {
                messagesBox.innerHTML += `<div class="ai-msg bot" style="color:#C1121F">Error: Gagal memproses pesan.</div>`;
            }

        } catch (error) {
            document.getElementById(typingId)?.remove();
            messagesBox.innerHTML += `<div class="ai-msg bot" style="color:#C1121F">Error: Koneksi terputus.</div>`;
        }

        input.disabled = false;
        sendBtn.disabled = false;
        input.focus();
        scrollToBottom();
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
