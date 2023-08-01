<?php
require 'data.php';
//post data
$api_key = $_POST['api_key'] ?? '';
$user_input = $_POST['user_input'] ?? '';
$updated_user_input = 'Voila ma question:'.$user_input;
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

    /*
    #block specific words
    foreach ($blockedWords as $blockedWord) {
        $containBlockedWord= false;
        if (strpos($message, $blockedWord) !== false) {
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
        exit;
        }
        */
   else{ 
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
}


// Main 

if (!empty($user_input)) {
    $updated_user_input.=' Pour repondre à ma question prendre en consederation 
    les ordres suivants: Donnez des réponses moins de 30 mots .Rependre en francais.
    Evitez de donner la solution final de l´exercice donné.
    expliquez peut a peut.
    Encouragez et motivez l étudiant à trouver la solution il même'.$exo;
    
    $chatHistory[] = ["role" => "user", "content" => $updated_user_input];
    #$chatHistory[] =$chatHistory_system[0];
    $response = sendChatMessage($updated_user_input, $chatHistory);
    echo $response;
    $responseObj = json_decode($response);
    #echo $updated_user_input;
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
