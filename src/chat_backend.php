<?php
//post data
$api_key = $_POST['api_key'] ?? '';
$user_input = $_POST['user_input'] ?? '';
$exo = $_POST['exo'] ?? '';
$order = $_POST['order'] ?? '';
$data_list = $_POST['dataArray'] ?? '';

$myArray=array();
$myArray = json_decode($data_list);

$blockedWords = ["solv","resoudr","solution mere", "solution à l'equation" , "solution finale","solution d'un problème"
,"solution de forme","solution composite","solutions analytiques","solution globale","solution finale",
"solution informatique","exercice résolu","exercice corrigé","réponse aux question","réponse à cette question",
"réponse exacte","réponse de type","réponse linéaire","solution mere","resoudre l exercice","résolution numérique des équations",
"résolution de problème","résolution numérique","résolution exercice"];
$allowedWords=["comment","aidez moi","j ai pas compri","j ai de mal a comprendr","explique plus","esseyez d expliquer"];

// Helper function to send a chat message to the OpenAI API
function sendChatMessage($message, $chatHistory) {
    global $api_key,$blockedWords,$allowedWords,$containBlockedWord,$containAllowedWord;

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
            echo("Il n'est pas approprié de fournir le résultat final ou la solution.
            Mon objectif est de vous guider pour trouver la solution par vous-même. ");
            return -1;
        }
    
    
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
    ["role" => "user", "content" =>  "parlez en francais.Rependre dans 10 ligne de text ou moin" ],
    ["role" => "user", "content" => "utiliser une approche étape par étape. encouragez-les à poser des questions
    .Guider  l'élève ne donnez pas la reponse."],
    ["role" => "user", "content" =>  $exo ],
];


if (!empty($user_input)) {
    $chatHistory[] = ["role" => "user", "content" => $user_input];
    $response = sendChatMessage($user_input, $chatHistory);
    $responseObj = json_decode($response);
    
    if (isset($responseObj->choices[0])) {
        $botMessage = $responseObj->choices[0]->message;
        $total_tokens = $responseObj->usage->total_tokens;
        echo 'you expend: '.$total_tokens.' tokens.'."\n\n";
        $chatHistory[] = $botMessage;
        $bot_response = $botMessage->content;
    } else {
        $bot_response = "";
    }

    echo $bot_response;
    exit;
}
?>
