<?php

include (__DIR__ . '/vendor/autoload.php');

$access_token = 'EAAE2Y3KjNyYBAHpKjqSZAmGN8YvpkC09Duh1deYrqV4ZC2NrOpPgbo9b4njEZCJOkusX8Yw1b6JiXX4ukvXIZBtn5ZCZBZBbfEa6VQV46p6P8O0MZAjNUIgNZAZAUrHOfVCqRPdO5v7ECtCZCok5es60GPmyVNHEUfbjZCldFmxk9qsNp9kfa8ZCTG45j';
$verify_token = 'TOKEN';
$appId = '341275729671974';
$appSecret = 'f7fbe7249cdffb4563e6635682a2eee2';

if(isset($_REQUEST['hub_challenge'])) {
    $challenge = $_REQUEST['hub_challenge'];
    if ($_REQUEST['hub_verify_token'] === $verify_token) {
        echo $challenge; die();
    }
}

$input = json_decode(file_get_contents('php://input'), true);
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

