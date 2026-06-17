<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Movie Recommender</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <style>
        .glass {
            background: rgba(17, 24, 39, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .glow-text { text-shadow: 0 0 30px rgba(139, 92, 246, 0.6); }
        
        /* Custom Scrollbar for the chat box */
        #chat-box::-webkit-scrollbar { width: 8px; }
        #chat-box::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
        #chat-box::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.2); border-radius: 4px; }
        
        /* Markdown formatting for the AI's response */
        .ai-message strong { color: #A78BFA; }
        .ai-message ul { margin-top: 10px; margin-bottom: 10px; padding-left: 20px; list-style-type: disc; }
        .ai-message li { margin-bottom: 5px; }

        #poster-bg {
            position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
            z-index: -1; overflow: hidden; pointer-events: none; opacity: 0.15; 
        }
        .floating-poster {
            position: absolute; width: 150px; border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.8);
            animation: floatUp linear infinite; bottom: -250px; 
        }
        @keyframes floatUp {
            0% { transform: translateY(0) scale(0.8) rotate(-5deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-120vh) scale(1.3) rotate(5deg); opacity: 0; }
        }
    </style>
</head>
<body class="bg-gray-950 text-white font-sans antialiased min-h-screen flex flex-col">

    <div id="poster-bg"></div>
    <?php include("nav.php"); ?>

    <main class="flex-grow flex items-center justify-center p-4">
        
        <div class="w-full max-w-4xl glass rounded-3xl shadow-2xl overflow-hidden flex flex-col" style="height: 80vh;">
            
<div class="p-6 border-b border-white/5 bg-black/20 flex items-center justify-between">
    
    <div>
        <h1 class="text-3xl font-bold text-blue-400 flex items-center gap-3">
            Ask Away
        </h1>
        <p class="text-sm text-gray-400 mt-1">Tell me your exact vibe. I'll search the cinematic universe.</p>
    </div>

    <a href="index.php" 
       class="text-gray-400 hover:text-white bg-white/5 hover:bg-white/10 p-2 rounded-xl border border-white/5 transition-all hover:scale-105"
       title="Exit Chat">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </a>
</div>

            <div id="chat-box" class="flex-grow p-6 overflow-y-auto flex flex-col gap-6">
                <div class="flex justify-start">
                    <div class="bg-white/5 border border-white/10 rounded-2xl rounded-tl-none px-6 py-4 max-w-[80%] ai-message text-gray-200">
                        Hello! I am your personal film curator. What kind of movie are you looking for today? 
                        <br><br>
                        <em>Try asking: "I want an 80s sci-fi movie that takes place in space but has a lot of comedy."</em>
                    </div>
                </div>
            </div>

            <div class="p-4 border-t border-white/5 bg-black/20">
                <form id="chat-form" class="flex gap-3">
                    <input type="text" id="user-input" autocomplete="off" placeholder="Describe your perfect movie..." 
                        class="flex-grow bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-white focus:outline-none focus:border-green-500 transition-colors placeholder-gray-500">
                    
                    <button type="submit" id="send-btn" 
                        class="bg-orange-400 hover:bg-blue-500 text-white px-8 py-4 rounded-xl font-bold transition-all hover:shadow-[0_0_20px_rgba(147,51,234,0.5)] flex items-center gap-2">
                        <span>Send</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                    </button>
                </form>
            </div>

        </div>
    </main>

    <script src="assets/posters.js"></script>

    <script>
        const chatForm = document.getElementById('chat-form');
        const userInput = document.getElementById('user-input');
        const chatBox = document.getElementById('chat-box');
        const sendBtn = document.getElementById('send-btn');

        // Function to add a message bubble to the screen
        function appendMessage(sender, text) {
            const wrapper = document.createElement('div');
            wrapper.className = `flex ${sender === 'user' ? 'justify-end' : 'justify-start'}`;

            const bubble = document.createElement('div');
            
            if (sender === 'user') {
                bubble.className = 'bg-blue-600 text-white rounded-2xl rounded-tr-none px-6 py-4 max-w-[80%] shadow-lg';
                bubble.innerText = text; // Plain text for user
            } else {
                bubble.className = 'bg-white/5 border border-white/10 rounded-2xl rounded-tl-none px-6 py-4 max-w-[80%] ai-message text-gray-200 shadow-lg';
                // Use marked.js to render Markdown (bolding, lists) beautifully
                bubble.innerHTML = marked.parse(text); 
            }

            wrapper.appendChild(bubble);
            chatBox.appendChild(wrapper);
            
            // Auto-scroll to the bottom
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        // Function to show a typing indicator
        function showTyping() {
            const wrapper = document.createElement('div');
            wrapper.id = 'typing-indicator';
            wrapper.className = 'flex justify-start';
            wrapper.innerHTML = `
                <div class="bg-white/5 border border-white/10 rounded-2xl rounded-tl-none px-6 py-4 flex items-center gap-2">
                    <div class="w-2 h-2 bg-orange-400 rounded-full animate-bounce" style="animation-delay: 0s;"></div>
                    <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-bounce" style="animation-delay: 0.4s;"></div>
                </div>
            `;
            chatBox.appendChild(wrapper);
            chatBox.scrollTop = chatBox.scrollHeight;
        }

        function removeTyping() {
            const indicator = document.getElementById('typing-indicator');
            if (indicator) indicator.remove();
        }

        // Handle the Submit event
        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const text = userInput.value.trim();
            if (!text) return;

            // 1. Show user's message
            appendMessage('user', text);
            userInput.value = '';
            
            // 2. Disable input and show typing animation
            userInput.disabled = true;
            sendBtn.disabled = true;
            showTyping();

            try {
                // 3. Send request to our secure PHP backend
                const response = await fetch('ai_handler.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ prompt: text })
                });

                const data = await response.json();
                
                removeTyping();

                // 4. Show AI response or error
                if (data.reply) {
                    appendMessage('ai', data.reply);
                } else if (data.error) {
                    appendMessage('ai', "🚨 Error: " + data.error);
                }

            } catch (error) {
                console.error(error);
                removeTyping();
                appendMessage('ai', "🚨 Connection failed. Please check your internet or server logs.");
            } finally {
                // 5. Re-enable input
                userInput.disabled = false;
                sendBtn.disabled = false;
                userInput.focus();
            }
        });
    </script>
</body>
</html>