let messageInput = document.getElementById('messageInput');
let sendButton = document.getElementById('sendButton');

document.addEventListener('DOMContentLoaded', function () {
    messageInput.focus();
    const chatMessages = document.getElementById('chatMessages');
    chatMessages.scrollTop = chatMessages.scrollHeight;
});

messageInput.addEventListener('input', function () {
    this.style.height = 'auto';
    this.style.height = (this.scrollHeight) + 'px';
});

messageInput.addEventListener('keydown', function (e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
});

sendButton.addEventListener('click', sendMessage);

function sendMessage() {
    let message = messageInput.value.trim();
    let typingIndicator = document.getElementById('typingIndicator');
    messageInput = document.getElementById('messageInput');

    if (!message) return;

    sendButton.disabled = true;
    messageInput.disabled = true;
    typingIndicator.style.display = 'block';

    appendMessage(message, 'user-message');
    messageInput.value = '';
    messageInput.focus();

    fetch('index.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `message=${encodeURIComponent(message)}`
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                appendMessage(data.response, 'assistant-message');
            } else {
                appendMessage('Desculpe, ocorreu um erro ao processar sua mensagem.', 'assistant-message');
            }
        })
        .catch(error => {
            appendMessage('Erro de conexão. Por favor, tente novamente.', 'assistant-message');
            console.error('Error:', error);
        })
        .finally(() => {
            sendButton.disabled = false;
            messageInput.disabled = false;
            typingIndicator.style.display = 'none';
            messageInput.focus();
        });
}

function appendMessage(message, className) {
    const chatMessages = document.getElementById('chatMessages');
    const messageDiv = document.createElement('div');
    messageDiv.className = `message ${className}`;
    messageDiv.textContent = message;
    chatMessages.appendChild(messageDiv);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}
