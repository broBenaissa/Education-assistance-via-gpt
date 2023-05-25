<?php
// OpenAI API configuration
$apiKey = "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";

// Helper function to send a chat message to the OpenAI API
function sendChatMessage($message, $chatHistory) {
    global $apiKey;
    
    $url = "https://api.openai.com/v1/chat/completions";
    
    $data = [
        "model" => "gpt-3.5-turbo",
        "messages" => $chatHistory,
    ];
    
    $headers = [
        "Content-Type: application/json",
        "Authorization: Bearer " . $apiKey,
    ];
    
    $options = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => $headers,
    ];
    
    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}

// Main loop
$chatHistory = [
    ["role" => "system", "content" => " "],
];

echo "Chatbot is ready. Enter your message:\n";
while (true) {
    $userInput = readline("user: ");
    $chatHistory[] = ["role" => "user", "content" => $userInput];
    
    $response = sendChatMessage($userInput, $chatHistory);
    $responseObj = json_decode($response);
  
    
    $botMessage = $responseObj->choices[0]->message;
    $chatHistory[] = $botMessage;
    
    echo "Bot: " . $botMessage->content . "\n";
}

?>