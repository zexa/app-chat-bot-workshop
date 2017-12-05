<?php

namespace Paysera\Workshop\ChatBot\Entity;

use Paysera\Workshop\ChatBot\Entity\Facebook\QuickReplyMessage;
use Paysera\Workshop\ChatBot\Entity\Quiz\Answer;

class QuizMessage
{
    /**
     * @var QuickReplyMessage
     */
    private $message;

    /**
     * @var Answer
     */
    private $correctAnswer;

    /**
     * @return QuickReplyMessage
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param QuickReplyMessage $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return Answer
     */
    public function getCorrectAnswer()
    {
        return $this->correctAnswer;
    }

    /**
     * @param Answer $correctAnswer
     *
     * @return $this
     */
    public function setCorrectAnswer($correctAnswer)
    {
        $this->correctAnswer = $correctAnswer;
        return $this;
    }
}
