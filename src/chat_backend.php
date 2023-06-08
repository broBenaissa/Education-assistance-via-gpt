<?php
require 'data.php';
//post data
$api_key = $_POST['api_key'] ?? '';
$user_input = $_POST['user_input'] ?? '';
$exo = $_POST['exo'] ?? '';
$order = $_POST['order'] ?? '';

// Helper function to send a chat message to the OpenAI API
function sendChatMessage($message, $chatHistory) {
    global $user_input,$api_key,$blockedWords,$allowedWords,$containBlockedWord,
    $containAllowedWord,$random_messages_short_question,$random_messages_solution,
    $french_words,$random_messages_language;

    
    #block short question
    $wordCount = str_word_count($user_input);
    if ($wordCount < 2) {
        sleep(2);
        // Output the random message
        $randomIndex = array_rand($random_messages_short_question);
        $randomMessage = $random_messages_short_question[$randomIndex];
        echo $randomMessage;
        exit;
    }

    #language detect
    $french_words_array=explode(' ',$french_words);
    $user_input_array=explode(' ',$user_input);
    $commonWords = array_intersect($french_words_array,$user_input_array);
    if (empty($commonWords)) {
        sleep(2);
        // Output the random message
        $randomIndex = array_rand($random_messages_language);
        $randomMessage = $random_messages_language[$randomIndex];
        echo $randomMessage;
        exit;
    }


    #block specific words
    foreach ($blockedWords as $blockedWord) {
        $containBlockedWord= false;
        if (stripos($message, $blockedWord) !== false) {
            $containBlockedWord= true;
            break;
        
        }
    }
        
    $containAllowedWord = false;
    foreach ($allowedWords as $allowedWord) {
        if (strpos($message, $allowedWord) !== false) {
            $containAllowedWord = true;
            break;
        }
    }

    if($containBlockedWord && !$containAllowedWord){
            sleep(2);
            // Output the random message
            $randomIndex = array_rand($random_messages_solution);
            $randomMessage = $random_messages_solution[$randomIndex];
            echo $randomMessage;
            return -1;
        }
    
    #send user input to gpt
    $url = "https://api.openai.com/v1/chat/completions";
    
    $data = [
        "model" => "gpt-3.5-turbo",
        "messages" => $chatHistory,
    ];
    
    $headers = [
        "Content-Type: application/json",
        "Authorization: Bearer " . $api_key,
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
    return $response ;
    
}


// Main 
$chatHistory = [
    ["role" => "system", "content" =>  "Vous Guidez  l'élève sans donner la reponse final de l'exercice."],
    ["role" => "user", "content" =>  "Vous parlez en francais.Vous Rependez dans 20 mots ou moin." ],
    ["role" => "user", "content" => "Vous Utiliser une approche étape par étape. encouragez-les à poser des questions."],
    ["role" => "user", "content" =>  $exo ],
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
        $bot_response = "";
    }

    echo $bot_response;
    exit;
}

?>
