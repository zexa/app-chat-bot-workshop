<?php

namespace Paysera\Workshop\ChatBot\Controller;

use Paysera\Workshop\ChatBot\Service\MessageSender;
use Paysera\Workshop\ChatBot\Service\MessagingExtractor;
use Paysera\Workshop\ChatBot\Service\QuizSessionManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class QuizController
{
    private $messagingExtractor;
    private $quizSessionManager;
    private $messageSender;

    public function __construct(
        MessagingExtractor $messagingExtractor,
        QuizSessionManager $quizSessionManager,
        MessageSender $messageSender
    ) {
        $this->messagingExtractor = $messagingExtractor;
        $this->quizSessionManager = $quizSessionManager;
        $this->messageSender = $messageSender;
    }

    public function playQuiz(Request $request)
    {
        $messagingRequest = $this->messagingExtractor->extractMessagingRequest($request);

        try {
            if ($this->quizSessionManager->isAnswerToQuestion($messagingRequest)) {
                $messagingResponse = $this->quizSessionManager->continueQuiz($messagingRequest);
            } elseif ($this->quizSessionManager->isRequestedToStartQuiz($messagingRequest->getMessage())) {
                $messagingResponse = $this->quizSessionManager->startQuiz($messagingRequest);
            } else {
                $messagingResponse = $this->quizSessionManager->buildUnrecognizedResponse($messagingRequest->getSender());
            }
        } catch (\Exception $quizNotStartedException) {
            $messagingResponse = $this->quizSessionManager->buildUnrecognizedResponse($messagingRequest->getSender());
        }

        $this->messageSender->sendMessagingResponse($messagingResponse);

        return new Response();
    }
}
