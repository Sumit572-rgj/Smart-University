<?php
$f = 'C:/xampp/htdocs/cit_ums/app/Views/dashboard/index.php';
$c = file_get_contents($f);

$chatbotUI = <<<'HTML'
<!-- AI Helpdesk Chatbot -->
<style>
    .chatbot-btn {
        position: fixed; bottom: 2rem; right: 2rem; width: 60px; height: 60px;
        background: var(--cit-orange); color: white; border-radius: 50%;
        display: flex; justify-content: center; align-items: center;
        font-size: 1.75rem; cursor: pointer; box-shadow: 0 4px 15px rgba(249,115,22,0.4);
        z-index: 9999; transition: transform 0.2s;
    }
    .chatbot-btn:hover { transform: scale(1.1); }
    
    .chatbot-window {
        position: fixed; bottom: 6rem; right: 2rem; width: 350px; height: 500px;
        background: white; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        display: none; flex-direction: column; z-index: 9999; border: 1px solid #e2e8f0;
        overflow: hidden; opacity: 0; transform: translateY(20px); transition: all 0.3s ease;
    }
    .chatbot-window.active { display: flex; opacity: 1; transform: translateY(0); }
    
    .chatbot-header {
        background: #0f172a; color: white; padding: 1rem; display: flex;
        justify-content: space-between; align-items: center; font-weight: 600;
    }
    .chatbot-close { cursor: pointer; font-size: 1.25rem; opacity: 0.7; }
    .chatbot-close:hover { opacity: 1; }
    
    .chatbot-messages {
        flex: 1; padding: 1rem; overflow-y: auto; display: flex; flex-direction: column; gap: 1rem;
        background: #f8fafc;
    }
    
    .msg-bubble { max-width: 80%; padding: 0.75rem 1rem; border-radius: 12px; font-size: 0.9rem; line-height: 1.4; }
    .msg-bot { background: white; border: 1px solid #e2e8f0; border-bottom-left-radius: 4px; align-self: flex-start; color: #334155; }
    .msg-user { background: var(--cit-orange); color: white; border-bottom-right-radius: 4px; align-self: flex-end; }
    
    .chatbot-input-area {
        padding: 1rem; border-top: 1px solid #e2e8f0; display: flex; gap: 0.5rem; background: white;
    }
    .chatbot-input {
        flex: 1; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 999px;
        outline: none; font-size: 0.9rem;
    }
    .chatbot-input:focus { border-color: var(--cit-orange); }
    .chatbot-send {
        background: var(--cit-orange); color: white; border: none; width: 40px; height: 40px;
        border-radius: 50%; display: flex; justify-content: center; align-items: center; cursor: pointer;
    }
    
    .typing-indicator { display: none; padding: 0.5rem 1rem; color: #94a3b8; font-size: 0.8rem; font-style: italic; }
</style>

<div class="chatbot-btn" onclick="toggleChatbot()">🤖</div>

<div class="chatbot-window" id="chatbot-window">
    <div class="chatbot-header">
        <div>🤖 CIT AI Helpdesk</div>
        <div class="chatbot-close" onclick="toggleChatbot()">✖</div>
    </div>
    <div class="chatbot-messages" id="chatbot-messages">
        <div class="msg-bubble msg-bot">
            Hi there! I am the CIT AI Assistant. How can I help you today?
        </div>
    </div>
    <div class="typing-indicator" id="chatbot-typing">Bot is typing...</div>
    <div class="chatbot-input-area">
        <input type="text" id="chatbot-input" class="chatbot-input" placeholder="Ask about fees, outpass..." onkeypress="handleChatEnter(event)">
        <button class="chatbot-send" onclick="sendChatMessage()">➤</button>
    </div>
</div>

<script>
    function toggleChatbot() {
        const win = document.getElementById('chatbot-window');
        if (win.style.display === 'flex') {
            win.style.opacity = '0';
            win.style.transform = 'translateY(20px)';
            setTimeout(() => win.style.display = 'none', 300);
        } else {
            win.style.display = 'flex';
            setTimeout(() => { win.style.opacity = '1'; win.style.transform = 'translateY(0)'; }, 10);
            document.getElementById('chatbot-input').focus();
        }
    }

    function handleChatEnter(e) {
        if (e.key === 'Enter') sendChatMessage();
    }

    async function sendChatMessage() {
        const input = document.getElementById('chatbot-input');
        const text = input.value.trim();
        if (!text) return;
        
        input.value = '';
        addMessage(text, 'user');
        
        document.getElementById('chatbot-typing').style.display = 'block';
        scrollToBottom();

        try {
            const res = await fetch('/cit_ums/chatbot/ask', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ message: text })
            });
            const data = await res.json();
            
            document.getElementById('chatbot-typing').style.display = 'none';
            addMessage(data.reply, 'bot');
        } catch (e) {
            document.getElementById('chatbot-typing').style.display = 'none';
            addMessage("Sorry, I'm having trouble connecting to the server.", 'bot');
        }
    }

    function addMessage(text, sender) {
        const msgs = document.getElementById('chatbot-messages');
        const div = document.createElement('div');
        div.className = 'msg-bubble msg-' + sender;
        div.innerHTML = text; // allow HTML links from bot
        msgs.appendChild(div);
        scrollToBottom();
    }
    
    function scrollToBottom() {
        const msgs = document.getElementById('chatbot-messages');
        msgs.scrollTop = msgs.scrollHeight;
    }
</script>

<script src="/cit_ums/js/sidebar.js
HTML;

$c = str_replace('<script src="/cit_ums/js/sidebar.js', $chatbotUI, $c);

file_put_contents($f, $c);
echo "Injected AI Chatbot UI.\n";
