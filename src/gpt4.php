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

$exo ="EOD Soit F =P/Q
où P et Q sont des polynômes tous deux non nuls et premiers entre eux. Montrer que F est paire si
et seulement si P et Q sont pairs. Etablir un résultat analogue pour F impaire.
EOD Soit F =P/Q
où P et Q sont des polynômes tous deux non nuls et premiers entre eux. Montrer que F est paire si
et seulement si P et Q sont pairs. Etablir un résultat analogue pour F impair
EOD Soit F =P/Q
où P et Q sont des polynômes tous deux non nuls et premiers entre eux. Montrer que F est paire si
et seulement si P et Q sont pairs. Etablir un résultat analogue pour F impair
EOD Soit F =P/Q
où P et Q sont des polynômes tous deux non nuls et premiers entre eux. Montrer que F est paire si
et seulement si P et Q sont pairs. Etablir un résultat analogue pour F impair";
// Main 
$chatHistory = [
   
    ["role" => "system", "content" => "Response should be 20 words or less"],
    ["role" => "system", "content" => "You are helpful smart ducational assistant,guide student but dont't provide exercise solutions."],
    ["role" => "system", "content" => $exo],

];


if (!empty($user_input)) {
    $chatHistory[] = ["role" => "user", "content" => $user_input];
    $response = sendChatMessage($user_input, $chatHistory);
    //echo $response."\n";                  
    $responseObj = json_decode($response);
   
    
    if (isset($responseObj->choices[0])) {
        $botMessage = $responseObj->choices[0]->message;
        $total_tokens = $responseObj->usage->total_tokens;
        echo 'you expend: '.$total_tokens.' tokens.'."\n\n";
        $chatHistory[] = $botMessage;
        $bot_response = $botMessage->content;
    } else {
        $bot_response = "Error - Unexpected response format";
    }

    echo $bot_response;
    exit;
}

?>
