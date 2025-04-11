<?php

use App\Services\Session;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    try {
        $result = handleChatRequest($_POST['message']);
        echo json_encode($result);
    } catch (Throwable $th) {
        echo json_encode([
            'success' => false,
            'message' => $th->getMessage()
        ]);
    }
    exit;
}
?>


<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/html">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Assistente Virtual</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="chat-container">
    <div class="chat-header">
        <h2>Prova de Conceito - Assistente Virtual com Llama3</h2>
    </div>

    <div class="chat-messages" id="chatMessages">
        <?php foreach (Session::getHistory() as $message): ?>
            <div class="message <?= $message['type'] === 'user' ? 'user-message' : 'assistant-message' ?>">
                <?= nl2br(htmlspecialchars($message['message'])) ?>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="typing-indicator" id="typingIndicator">
        O assistente está digitando...
    </div>

    <div class="chat-input">
        <label for="messageInput"></label>
        <textarea id="messageInput" placeholder="Digite sua mensagem..." rows="3"></textarea>
        <button id="sendButton">Enviar</button>
    </div>
</div>
<script src="./js/script.js"></script>
</body>
</html>



