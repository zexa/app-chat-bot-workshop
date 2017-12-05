<?php

use Facebook\Facebook;
use GuzzleHttp\Client;
use Paysera\Workshop\ChatBot\Controller\QuizController;
use Paysera\Workshop\ChatBot\Controller\VerificationController;
use Paysera\Workshop\ChatBot\Hub;
use Paysera\Workshop\ChatBot\Normalizer\AnswerNormalizer;
use Paysera\Workshop\ChatBot\Normalizer\MessageNormalizer;
use Paysera\Workshop\ChatBot\Normalizer\MessagingRequestNormalizer;
use Paysera\Workshop\ChatBot\Normalizer\MessagingResponseNormalizer;
use Paysera\Workshop\ChatBot\Normalizer\QuestionNormalizer;
use Paysera\Workshop\ChatBot\Normalizer\QuickReplyNormalizer;
use Paysera\Workshop\ChatBot\Normalizer\UserNormalizer;
use Paysera\Workshop\ChatBot\Service\Configuration;
use Paysera\Workshop\ChatBot\Service\MessageSender;
use Paysera\Workshop\ChatBot\Service\MessagingExtractor;
use Paysera\Workshop\ChatBot\Service\QuizMessageBuilder;
use Paysera\Workshop\ChatBot\Service\QuizProvider\OpenTDBProvider;
use Paysera\Workshop\ChatBot\Service\QuizSessionManager;
use Paysera\Workshop\ChatBot\Service\SessionStorage\FileSessionStorage;
use Symfony\Component\HttpFoundation\Request;

include (__DIR__ . '/vendor/autoload.php');

$configuration = new Configuration(__DIR__ . 'config.json');
$request = Request::createFromGlobals();

if ($request->get(Hub::HUB_CHALLENGE) !== null) {
    $verificationController = new VerificationController($configuration->get('verify_token'));
    $verificationController->verify($request)->send();
    exit();
}

$questionsCount = 10;
$quickReplyNormalizer = new QuickReplyNormalizer();
$userNormalizer = new UserNormalizer();
$messageNormalizer = new MessageNormalizer($quickReplyNormalizer);
$messagingRequestNormalizer = new MessagingRequestNormalizer($messageNormalizer, $userNormalizer);
$messagingExtractor = new MessagingExtractor($messagingRequestNormalizer);
$sessionStorage = new FileSessionStorage(__DIR__ . '/session.csv');
$openTDBClient = new Client();
$answerNormalizer = new AnswerNormalizer();
$questionNormalizer = new QuestionNormalizer($answerNormalizer);
$quizProvider = new OpenTDBProvider($openTDBClient, $questionNormalizer);
$quizMessageBuilder = new QuizMessageBuilder($quizProvider);
$quizSessionManager = new QuizSessionManager($sessionStorage, $questionsCount, $quizMessageBuilder);
$facebook = new Facebook([
    'app_id' => $configuration->get('app_id'),
    'app_secret' => $configuration->get('app_secret'),
]);
$messagingResponseNormalizer = new MessagingResponseNormalizer($messageNormalizer, $userNormalizer);
$messageSender = new MessageSender($facebook, $configuration->get('access_token'), $messagingResponseNormalizer);
$quizController = new QuizController($messagingExtractor, $quizSessionManager, $messageSender);

$quizController->playQuiz($request)->send();

exit();
