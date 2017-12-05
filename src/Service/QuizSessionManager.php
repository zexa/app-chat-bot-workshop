<?php

namespace Paysera\Workshop\ChatBot\Service;

use Paysera\Workshop\ChatBot\Entity\Facebook\Message;
use Paysera\Workshop\ChatBot\Entity\Facebook\MessagingRequest;
use Paysera\Workshop\ChatBot\Entity\Facebook\MessagingResponse;
use Paysera\Workshop\ChatBot\Entity\Facebook\QuickReplyMessage;
use Paysera\Workshop\ChatBot\Entity\Facebook\User;
use Paysera\Workshop\ChatBot\Entity\QuizSession;
use Paysera\Workshop\ChatBot\Exception\InvalidMessageTypeException;
use Paysera\Workshop\ChatBot\Exception\QuizProviderException;
use Paysera\Workshop\ChatBot\Service\SessionStorage\SessionStorageInterface;

class QuizSessionManager
{
    private $sessionStorage;
    private $questionsCount;
    private $quizMessageBuilder;

    /**
     * @param SessionStorageInterface $sessionStorage
     * @param int $questionsCount
     * @param QuizMessageBuilder $quizMessageBuilder
     */
    public function __construct(
        SessionStorageInterface $sessionStorage,
        $questionsCount,
        QuizMessageBuilder $quizMessageBuilder
    ) {
        $this->sessionStorage = $sessionStorage;
        $this->questionsCount = $questionsCount;
        $this->quizMessageBuilder = $quizMessageBuilder;
    }

    public function isRequestedToStartQuiz(Message $message)
    {
        return in_array(strtolower($message->getText()), ['lets play'], true);
    }

    /**
     * @param User $sender
     * @return MessagingResponse
     */
    public function buildUnrecognizedResponse(User $sender)
    {
        return (new MessagingResponse())
            ->setRecipient($sender)
            ->setMessage((new Message())->setText('To play Quiz please write "lets play" or pick correct answer'))
        ;
    }

    public function isAnswerToQuestion(MessagingRequest $messagingRequest)
    {
        return $messagingRequest->getMessage() instanceof QuickReplyMessage
            && $this->isQuizRunning($messagingRequest->getSender())
        ;
    }

    /**
     * @param MessagingRequest $messagingRequest
     * @return MessagingResponse
     * @throws InvalidMessageTypeException
     * @throws QuizProviderException
     */
    public function continueQuiz(MessagingRequest $messagingRequest)
    {
        if (!$this->isAnswerToQuestion($messagingRequest)) {
            throw new InvalidMessageTypeException('Message must be of type QuickReplyMessage');
        }

        $sender = $messagingRequest->getSender();
        $currentSession = $this->sessionStorage->getQuizSession($sender);

        $currentSession
            ->incrementTotalAnswersCount()
            ->setLastRepliedAt(new \DateTime())
        ;

        /** @var QuickReplyMessage $message */
        $message = $messagingRequest->getMessage();

        if ($currentSession->getLastCorrectAnswer()->getValue() === $message->getReplies()[0]->getPayload()) {
            $currentSession->incrementCorrectAnswersCount();
        }

        if ($currentSession->getTotalAnswersCount() === $this->questionsCount) {
            $this->sessionStorage->deleteQuizSession($currentSession);
            return (new MessagingResponse())
                ->setRecipient($sender)
                ->setMessage((new Message())->setText(sprintf(
                    'You got %s of %s answers right',
                    $currentSession->getCorrectAnswersCount(),
                    $currentSession->getTotalAnswersCount()
                )))
            ;
        }

        return $this->buildNextQuestionMessagingResponse($messagingRequest, $currentSession);
    }

    /**
     * @param MessagingRequest $messagingRequest
     * @return MessagingResponse
     * @throws QuizProviderException
     */
    public function startQuiz(MessagingRequest $messagingRequest)
    {
        $currentSession = (new QuizSession())
            ->setUser($messagingRequest->getSender())
        ;

        return $this->buildNextQuestionMessagingResponse($messagingRequest, $currentSession);
    }

    /**
     * @param MessagingRequest $messagingRequest
     * @param QuizSession $quizSession
     * @return MessagingResponse
     * @throws QuizProviderException
     */
    private function buildNextQuestionMessagingResponse(MessagingRequest $messagingRequest, QuizSession $quizSession)
    {
        $quizMessage = $this->quizMessageBuilder->buildQuizMessage();
        $quizSession->setLastCorrectAnswer($quizMessage->getCorrectAnswer());
        $this->sessionStorage->saveQuizSession($quizSession);

        return (new MessagingResponse())
            ->setRecipient($messagingRequest->getSender())
            ->setMessage($quizMessage->getMessage())
        ;
    }

    private function isQuizRunning(User $user)
    {
        $currentSession = $this->sessionStorage->getQuizSession($user);
        return $currentSession !== null && $currentSession->getTotalAnswersCount() <= $this->questionsCount;
    }
}
