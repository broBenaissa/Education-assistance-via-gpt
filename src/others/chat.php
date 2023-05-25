<?php
require '../vendor/autoload.php';

use OpenAI\OpenAI;

$openaiApiKey = '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';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $content = $_POST['content'];
    $chatHistory = $_POST['chat_history'];

    $chatbot = $openai->completeChat([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => 'You are a helpful assistant for education.dont give direct answer or solution'],
            ['role' => 'user', 'content' => $content]
        ]
    ], $openaiApiKey);

    $chatHistory[] = ['role' => 'user', 'content' => $content];
    $chatHistory[] = ['role' => 'assistant', 'content' => $chatbot->choices[0]->message->content];

    sleep(1);

    header('Content-Type: application/json');
    echo json_encode([
        'chat_history' => $chatHistory
    ]);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Chatbot</title>
</head>
<body>
    <div id="chat-container"  style="text-align:center">
        <div id="chat-history"></div>
        <form id="message-form">
            <input type="text" id="message-input" placeholder="Enter your message">
            <button type="submit">Send</button>
        </form>
        <button id="clear-button">Clear</button>
    </div>

    <script>
        const chatContainer = document.getElementById('chat-container');
        const chatHistory = document.getElementById('chat-history');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');
        const clearButton = document.getElementById('clear-button');
        let chatHistoryData = [];

        messageForm.addEventListener('submit', (event) => {
            event.preventDefault();
            const content = messageInput.value.trim();
            if (content === '') {
                return;
            }

            sendMessage(content);
            messageInput.value = '';
        });

        clearButton.addEventListener('click', () => {
            clearChatHistory();
        });

        function sendMessage(content) {
            chatHistoryData.push({ role: 'user', content });
            renderChatHistory();

            fetch('/', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `content=${encodeURIComponent(content)}&chat_history=${encodeURIComponent(JSON.stringify(chatHistoryData))}`
            })
                .then(response => response.json())
                .then(data => {
                    const { chat_history } = data;
                    chatHistoryData = chat_history;
                    renderChatHistory();
                })
                .catch(error => {
                    console.error(error);
                });
        }

        function renderChatHistory() {
            chatHistory.innerHTML = '';
            chatHistoryData.forEach(message => {
                const messageElement = document.createElement('div');
                messageElement.textContent = `${message.role}: ${message.content}`;
                chatHistory.appendChild(messageElement);
            });
        }

        function clearChatHistory() {
            chatHistoryData = [];
            renderChatHistory();
        }
    </script>

</html>
