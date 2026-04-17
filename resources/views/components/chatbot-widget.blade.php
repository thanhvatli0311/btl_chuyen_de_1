<!-- Chatbot Widget -->
@php
    $isAuthenticated = auth()->check();
    $loginUrl = url('/login');
@endphp

<div id="chatbot-widget" class="chatbot-widget" data-authenticated="{{ $isAuthenticated ? '1' : '0' }}">
    <div class="chatbot-header">
        <div>
            <h5 class="mb-0">🤖 Trợ lý ảo</h5>
            <small class="chatbot-header-subtitle">
                {{ $isAuthenticated ? 'Hỗ trợ sản phẩm, đơn hàng và tư vấn nhanh' : 'Vui lòng đăng nhập để bắt đầu trò chuyện' }}
            </small>
        </div>
        <button id="chatbot-close" class="chatbot-close-btn" type="button" aria-label="Đóng chatbot">×</button>
    </div>

    <div id="chatbot-messages" class="chatbot-messages"></div>

    <div class="chatbot-input-area">
        @if(!$isAuthenticated)
            <div class="chatbot-login-notice">
                <div class="chatbot-system-card chatbot-notice-card">
                    <strong>🔒 Cần đăng nhập</strong>
                    <span>Đăng nhập để xem lịch sử trò chuyện và gửi câu hỏi cho chatbot hoặc admin.</span>
                    <a href="{{ $loginUrl }}" class="btn btn-sm btn-primary mt-2">Đăng nhập ngay</a>
                </div>
            </div>
        @endif

        <form id="chatbot-form">
            <div class="input-group">
                <input
                    type="text"
                    id="chatbot-input"
                    class="form-control"
                    placeholder="{{ $isAuthenticated ? 'Nhập câu hỏi của bạn...' : 'Vui lòng đăng nhập để chat' }}"
                    autocomplete="off"
                    {{ $isAuthenticated ? '' : 'disabled' }}
                    required
                >
                <button class="btn btn-primary" id="chatbot-send-button" type="submit" {{ $isAuthenticated ? '' : 'disabled' }}>
                    Gửi
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Chatbot Toggle Button -->
<button id="chatbot-toggle" class="chatbot-toggle" title="Mở trợ lý ảo" type="button">
    🤖
</button>

<style>
.chatbot-widget {
    display: none;
    position: fixed;
    right: 20px;
    bottom: 80px;
    width: 370px;
    max-width: calc(100vw - 24px);
    height: 560px;
    max-height: calc(100vh - 110px);
    background: #ffffff;
    border-radius: 18px;
    box-shadow: 0 18px 50px rgba(0, 0, 0, 0.18);
    z-index: 9999;
    flex-direction: column;
    overflow: hidden;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    border: 1px solid rgba(102, 126, 234, 0.12);
}

.chatbot-widget.active {
    display: flex;
}

.chatbot-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    padding: 14px 16px;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
}

.chatbot-header h5 {
    margin: 0;
    font-weight: 700;
    font-size: 16px;
}

.chatbot-header-subtitle {
    display: block;
    margin-top: 4px;
    font-size: 12px;
    opacity: 0.9;
}

.chatbot-close-btn {
    border: none;
    background: transparent;
    color: #ffffff;
    font-size: 28px;
    line-height: 1;
    padding: 0;
    cursor: pointer;
}

.chatbot-messages {
    flex: 1;
    overflow-y: auto;
    padding: 16px;
    background: linear-gradient(180deg, #f8f9ff 0%, #f4f6fb 100%);
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.chatbot-message {
    display: flex;
    flex-direction: column;
    max-width: 88%;
}

.chatbot-message.user-message {
    align-self: flex-end;
    align-items: flex-end;
}

.chatbot-message.bot-message,
.chatbot-message.system-message {
    align-self: flex-start;
    align-items: flex-start;
}

.chatbot-bubble {
    padding: 11px 13px;
    border-radius: 14px;
    font-size: 13px;
    line-height: 1.5;
    word-break: break-word;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
}

.user-message .chatbot-bubble {
    background: linear-gradient(135deg, #667eea 0%, #5a67d8 100%);
    color: #ffffff;
    border-bottom-right-radius: 6px;
}

.bot-message .chatbot-bubble {
    background: #ffffff;
    color: #24324a;
    border: 1px solid #dde5f4;
    border-bottom-left-radius: 6px;
}

.system-message .chatbot-bubble {
    background: #fff8e8;
    color: #7a5d11;
    border: 1px solid #f2dfad;
    border-bottom-left-radius: 6px;
}

.chatbot-meta {
    margin-top: 5px;
    font-size: 11px;
    color: #7f8aa3;
    padding: 0 4px;
}

.chatbot-input-area {
    padding: 14px;
    background: #ffffff;
    border-top: 1px solid #e9edf6;
}

.chatbot-input-area form {
    margin: 0;
}

#chatbot-input {
    font-size: 13px;
    border-radius: 10px 0 0 10px;
}

#chatbot-send-button {
    border-radius: 0 10px 10px 0;
    min-width: 68px;
}

#chatbot-input:disabled,
#chatbot-send-button:disabled {
    cursor: not-allowed;
}

.chatbot-toggle {
    position: fixed;
    right: 20px;
    bottom: 20px;
    width: 56px;
    height: 56px;
    border: none;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: #ffffff;
    font-size: 24px;
    cursor: pointer;
    box-shadow: 0 12px 28px rgba(102, 126, 234, 0.35);
    z-index: 9998;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.chatbot-toggle:hover {
    transform: translateY(-2px) scale(1.04);
    box-shadow: 0 16px 32px rgba(102, 126, 234, 0.42);
}

.chatbot-system-card {
    padding: 12px 13px;
    border-radius: 12px;
    font-size: 13px;
    line-height: 1.5;
}

.chatbot-notice-card {
    background: #fff8e8;
    border: 1px solid #f2dfad;
    color: #7a5d11;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.chatbot-empty-state {
    background: #ffffff;
    border: 1px dashed #d5def0;
    color: #50607d;
}

.chatbot-error-state {
    background: #fff0f0;
    border: 1px solid #f2c3c3;
    color: #a94442;
}

.chatbot-login-notice {
    margin-bottom: 12px;
}

.chatbot-loading {
    display: flex;
    align-items: center;
    gap: 4px;
    min-height: 18px;
}

.chatbot-loading span {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #667eea;
    animation: chatbot-bounce 1.4s infinite ease-in-out;
}

.chatbot-loading span:nth-child(2) {
    animation-delay: 0.2s;
}

.chatbot-loading span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes chatbot-bounce {
    0%, 80%, 100% {
        opacity: 0.45;
        transform: translateY(0);
    }
    40% {
        opacity: 1;
        transform: translateY(-6px);
    }
}

@media (max-width: 480px) {
    .chatbot-widget {
        right: 10px;
        bottom: 74px;
        width: calc(100vw - 20px);
        height: 72vh;
        max-height: 72vh;
    }

    .chatbot-toggle {
        right: 14px;
        bottom: 14px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const chatbotWidget = document.getElementById('chatbot-widget');
    const chatbotToggle = document.getElementById('chatbot-toggle');
    const chatbotClose = document.getElementById('chatbot-close');
    const chatbotForm = document.getElementById('chatbot-form');
    const chatbotInput = document.getElementById('chatbot-input');
    const chatbotMessages = document.getElementById('chatbot-messages');
    const chatbotSendButton = document.getElementById('chatbot-send-button');
    const isAuthenticated = chatbotWidget.dataset.authenticated === '1';
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
    const historyUrl = '{{ route("chatbot.history") }}';
    const sendUrl = '{{ route("chatbot.send") }}';
    let historyLoaded = false;

    chatbotToggle.addEventListener('click', function () {
        chatbotWidget.classList.toggle('active');

        if (chatbotWidget.classList.contains('active')) {
            if (!historyLoaded) {
                loadHistory();
            }

            if (isAuthenticated) {
                chatbotInput.focus();
            }
        }
    });

    chatbotClose.addEventListener('click', function () {
        chatbotWidget.classList.remove('active');
    });

    chatbotForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (!isAuthenticated) {
            return;
        }

        const message = chatbotInput.value.trim();

        if (!message) {
            return;
        }

        appendMessage({
            sender: 'user',
            text: message,
            time: new Date().toISOString()
        });

        chatbotInput.value = '';
        setSendingState(true);
        const loadingId = showLoading();

        try {
            const response = await fetch(sendUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    message: message,
                    visitor_name: '{{ auth()->user()->name ?? "" }}',
                    visitor_email: '{{ auth()->user()->email ?? "" }}'
                })
            });

            const data = await response.json();
            removeLoading(loadingId);

            if (!response.ok || !data.success) {
                appendMessage({
                    sender: 'system',
                    text: data.message || '❌ Có lỗi xảy ra. Vui lòng thử lại sau.'
                });
                setSendingState(false);
                return;
            }

            if (data.response) {
                appendMessage({
                    sender: 'bot',
                    text: data.response,
                    sourceLabel: data.is_auto_reply ? 'Trả lời tự động' : 'Phản hồi'
                });
            } else {
                appendMessage({
                    sender: 'system',
                    text: data.message || '⏳ Tin nhắn của bạn đã được gửi và đang chờ admin phản hồi.'
                });
            }

            setSendingState(false);
        } catch (error) {
            removeLoading(loadingId);
            appendMessage({
                sender: 'system',
                text: '❌ Lỗi kết nối. Vui lòng thử lại.'
            });
            setSendingState(false);
            console.error('Chatbot error:', error);
        }
    });

    async function loadHistory() {
        historyLoaded = true;
        chatbotMessages.innerHTML = '';
        const loadingId = showLoading();

        if (!isAuthenticated) {
            removeLoading(loadingId);
            renderInitialGreeting();
            return;
        }

        try {
            const response = await fetch(historyUrl, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();
            removeLoading(loadingId);

            if (!response.ok || !data.success) {
                chatbotMessages.innerHTML = '';
                appendSystemCard('❌ Không thể tải lịch sử trò chuyện lúc này.', 'chatbot-error-state');
                return;
            }

            renderHistory(Array.isArray(data.messages) ? data.messages : []);
        } catch (error) {
            removeLoading(loadingId);
            chatbotMessages.innerHTML = '';
            appendSystemCard('❌ Không thể kết nối để tải lịch sử trò chuyện.', 'chatbot-error-state');
            console.error('Chat history error:', error);
        }
    }

    function renderHistory(messages) {
        chatbotMessages.innerHTML = '';

        if (!messages.length) {
            renderInitialGreeting();
            return;
        }

        messages.forEach(function (row) {
            if (row.message) {
                appendMessage({
                    sender: 'user',
                    text: row.message,
                    time: row.created_at
                });
            }

            if (row.response) {
                appendMessage({
                    sender: 'bot',
                    text: row.response,
                    time: row.updated_at || row.created_at,
                    sourceLabel: row.is_auto_reply ? 'Trả lời tự động' : 'Admin phản hồi'
                });
            } else if (row.status === 'pending') {
                appendMessage({
                    sender: 'system',
                    text: '⏳ Tin nhắn này đang chờ admin phản hồi.',
                    time: row.created_at
                });
            }
        });
    }

    function renderInitialGreeting() {
        appendMessage({
            sender: 'bot',
            text: '👋 Xin chào! Tôi là trợ lý ảo của Watch Store. Hãy gửi câu hỏi về sản phẩm, dịch vụ hoặc đơn hàng, tôi sẽ hỗ trợ bạn.'
        });
    }

    function appendMessage(options) {
        const sender = options.sender || 'bot';
        const text = options.text || '';
        const time = options.time || null;
        const sourceLabel = options.sourceLabel || null;
        const wrapper = document.createElement('div');
        wrapper.className = 'chatbot-message ' + sender + '-message';

        const bubble = document.createElement('div');
        bubble.className = 'chatbot-bubble';
        bubble.textContent = text;
        wrapper.appendChild(bubble);

        if (time || sourceLabel) {
            const meta = document.createElement('div');
            meta.className = 'chatbot-meta';
            meta.textContent = [sourceLabel, formatDateTime(time)].filter(Boolean).join(' • ');
            wrapper.appendChild(meta);
        }

        chatbotMessages.appendChild(wrapper);
        scrollMessagesToBottom();
    }

    function appendSystemCard(text, extraClass) {
        const card = document.createElement('div');
        card.className = 'chatbot-system-card ' + (extraClass || 'chatbot-empty-state');
        card.textContent = text;
        chatbotMessages.appendChild(card);
        scrollMessagesToBottom();
    }

    function showLoading() {
        const loadingId = 'chatbot-loading-' + Date.now();
        const wrapper = document.createElement('div');
        wrapper.className = 'chatbot-message bot-message';
        wrapper.id = loadingId;

        const bubble = document.createElement('div');
        bubble.className = 'chatbot-bubble';

        const loading = document.createElement('div');
        loading.className = 'chatbot-loading';
        loading.innerHTML = '<span></span><span></span><span></span>';

        bubble.appendChild(loading);
        wrapper.appendChild(bubble);
        chatbotMessages.appendChild(wrapper);
        scrollMessagesToBottom();

        return loadingId;
    }

    function removeLoading(loadingId) {
        const loading = document.getElementById(loadingId);

        if (loading) {
            loading.remove();
        }
    }

    function setSendingState(isSending) {
        if (!isAuthenticated) {
            return;
        }

        chatbotInput.disabled = isSending;
        chatbotSendButton.disabled = isSending;
    }

    function scrollMessagesToBottom() {
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    function formatDateTime(value) {
        if (!value) {
            return '';
        }

        const date = new Date(value);

        if (Number.isNaN(date.getTime())) {
            return '';
        }

        return date.toLocaleString('vi-VN', {
            hour: '2-digit',
            minute: '2-digit',
            day: '2-digit',
            month: '2-digit'
        });
    }
});
</script>