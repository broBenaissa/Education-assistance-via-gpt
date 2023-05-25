<?php
//post data
$user_input = $_POST['user_input'] ?? '';
// OpenAI API configuration
$apiKey = "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";

// Helper function to send a chat message to the OpenAI API
function sendChatMessage($message, $chatHistory) {
    global $apiKey;
    
    $url = "https://api.openai.com/v1/chat/completions";
    
    $data = [
        "model" => "gpt-4-0314",
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
    
    return print_r($response);//show response
    
}

// Main 
$chatHistory = [
   
    ["role" => "system", "content" => "Short answer."],
    ["role" => "system", "content" => "Educational assistant, no direct answers."],
];


if (!empty($user_input)) {
    $chatHistory[] = ["role" => "user", "content" => $user_input];
    $response = sendChatMessage($user_input, $chatHistory);
    $responseObj = json_decode($response);
    
    if (isset($responseObj->choices[0])) {
        $botMessage = $responseObj->choices[0]->message;
        $chatHistory[] = $botMessage;
        $bot_response = $botMessage->content;
    } else {
        $bot_response = "Error - Unexpected response format";
    }

    echo $bot_response;
    exit;
}

?>
