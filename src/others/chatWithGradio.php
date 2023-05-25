<?php
require 'vendor/autoload.php';


use OpenAI\OpenAI;

$openaiApiKey = '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';

$order = "As an educational assistant, guide students through exercises and provide helpful hints. Assist students in understanding the problem, suggest problem-solving strategies, and provide relevant information. Remember, the goal is to empower students to find the solution on their own and enhance their learning experience.";

function openaiCreate() {
    global $openai, $order;
    
    $response = $openai->completions->create([
        'model' => 'text-davinci-003',
        'prompt' => $order,
        'temperature' => 0.9,
        'max_tokens' => 150,
        'top_p' => 1,
        'frequency_penalty' => 0.0,
        'presence_penalty' => 0.6,
        'stop' => [" Human:", " AI:"]
    ]);

    $reply = $response['choices'][0]['text'];
    return trim($reply);
}

//$demo = new Gradio\Blocks();

//$chatbot = new Gradio\Chatbot();

//$msg = new Gradio\Textbox();

//$clear = new Gradio\Button('Clear');

$demo->append($chatbot, $msg);

function respond($message, $chat_history) {
    global $msg, $chatbot;

    $bot_message = openaiCreate();
    $chat_history[] = [$message, $bot_message];
    sleep(1);
    return $chat_history;
}

$msg->setSubmitAction('respond', $msg, $chatbot);

$clear->setClickAction(function () {
    // Clear button action
}, $chatbot, false);

$demo->launch();
?>
