<?php

include (__DIR__ . '/vendor/autoload.php');

$access_token = 'your_access_token_here';
$verify_token = 'TOKEN';
$appId = 'your_app_id_here';
$appSecret = 'your_app_secret_here';

if(isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    if ($_REQUEST['hub_verify_token'] === $verify_token) {
        echo $challenge; die();
    }
}

$input = json_decode(file_get_contents('php://input'), true);

if ($input === null) {
    exit;
}

$message = $input['entry'][0]['messaging'][0]['message']['text'];
$sender = $input['entry'][0]['messaging'][0]['sender']['id'];

$fb = new \Facebook\Facebook([
    'app_id' => $appId,
    'app_secret' => $appSecret,
]);

$data = [
    'recipient' => [
        'id' => $sender,
    ],
    'message' => [
        'text' => 'You wrote: ' . $message,
    ]
];

$response = $fb->post('/me/messages', $data, $access_token);
