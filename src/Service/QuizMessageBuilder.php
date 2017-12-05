<?php

namespace Paysera\Workshop\ChatBot\Service;

use Paysera\Workshop\ChatBot\Entity\Facebook\QuickReply;
use Paysera\Workshop\ChatBot\Entity\Facebook\QuickReplyMessage;
use Paysera\Workshop\ChatBot\Entity\Quiz\Answer;
use Paysera\Workshop\ChatBot\Entity\QuizMessage;
use Paysera\Workshop\ChatBot\Exception\QuizProviderException;
use Paysera\Workshop\ChatBot\Service\QuizProvider\QuizProviderInterface;

class QuizMessageBuilder
{
    private $quizProvider;

    public function __construct(QuizProviderInterface $quizProvider)
    {
        $this->quizProvider = $quizProvider;
    }

    /**
     * @return QuizMessage
     * @throws QuizProviderException
     */
    public function buildQuizMessage()
    {
        $quizQuestion = $this->quizProvider->getNextQuestion();

        /** @var Answer[] $answers */
        $answers = array_merge(
            $quizQuestion->getIncorrectAnswers(),
            [$quizQuestion->getCorrectAnswer()]
        );
        shuffle($answers);

        $message = (new QuickReplyMessage())->setText($quizQuestion->getDescription());
        foreach ($answers as $answer) {
            $quickReply = new QuickReply();
            $quickReply
                ->setPayload($answer->getValue())
                ->setTitle($answer->getValue())
            ;
            $message->addReply($quickReply);
        }

        return (new QuizMessage())
            ->setCorrectAnswer($quizQuestion->getCorrectAnswer())
            ->setMessage($message)
        ;
    }
}
