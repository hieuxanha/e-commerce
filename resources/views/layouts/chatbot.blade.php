<!-- Chat button -->
<div id="chat-widget" style="position: fixed; bottom: 20px; right: 20px; z-index: 3000;">
    <button id="chat-toggle" style="background:#0d9488; color:white; border:none; padding:14px; border-radius:50%; box-shadow:0 4px 8px rgba(0,0,0,.2);">
        💬
    </button>
</div>

<!-- Chat window -->
<div id="chat-box" style="display:none; position:fixed; bottom:80px; right:20px; width:320px; height:420px; background:#fff; border-radius:12px; box-shadow:0 8px 24px rgba(0,0,0,.2); overflow:hidden; flex-direction:column; z-index:3000;">
    <div style="background:#0d9488; padding:10px; color:white; font-weight:bold;">Chat hỗ trợ</div>
    <div id="chat-messages" style="flex:1; padding:10px; overflow-y:auto; font-size:14px;"></div>
    <div style="padding:10px; display:flex; gap:6px;">
        <input id="chat-input" type="text" placeholder="Nhập tin nhắn..." style="flex:1; padding:6px; border:1px solid #ccc; border-radius:6px;">
        <button id="chat-send" style="background:#0d9488; color:white; border:none; padding:6px 12px; border-radius:6px;">Gửi</button>
    </div>
</div>

<script>
    (function() {
        const box = document.getElementById("chat-box");
        const btnToggle = document.getElementById("chat-toggle");
        const btnSend = document.getElementById("chat-send");
        const input = document.getElementById("chat-input");
        const messages = document.getElementById("chat-messages");
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        btnToggle.addEventListener("click", () => {
            box.style.display = box.style.display === "none" ? "flex" : "none";
            if (box.style.display === "flex") input.focus();
        });

        function appendLine(role, text) {
            const row = document.createElement('div');
            const who = document.createElement('b');
            who.textContent = role + ': ';
            row.appendChild(who);
            // tránh XSS: dùng textContent
            row.appendChild(document.createTextNode(text));
            messages.appendChild(row);
            messages.scrollTop = messages.scrollHeight;
        }

        async function sendMsg() {
            const msg = (input.value || '').trim();
            if (!msg) return;

            appendLine('Bạn', msg);
            input.value = '';
            input.focus();

            // hiển thị trạng thái đang trả lời
            const typing = document.createElement('div');
            typing.id = 'typing';
            typing.textContent = 'Bot đang trả lời...';
            typing.style.color = '#6b7280';
            messages.appendChild(typing);
            messages.scrollTop = messages.scrollHeight;

            try {
                const res = await fetch(`{{ route('chat.ask') }}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': CSRF,
                    },
                    body: JSON.stringify({
                        message: msg
                    })
                });

                const data = await res.json().catch(() => ({}));
                typing.remove();

                const reply = data && data.reply ? String(data.reply) : 'Xin lỗi, mình chưa hiểu ý bạn.';
                appendLine('Bot', reply);
            } catch (e) {
                typing.remove();
                appendLine('Bot', 'Có lỗi mạng khi gửi tin. Vui lòng thử lại.');
            }
        }

        btnSend.addEventListener('click', sendMsg);
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') sendMsg();
        });
    })();
</script>