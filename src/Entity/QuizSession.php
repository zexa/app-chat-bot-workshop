<?php

namespace Paysera\Workshop\ChatBot\Entity;

use Paysera\Workshop\ChatBot\Entity\Facebook\User;
use Paysera\Workshop\ChatBot\Entity\Quiz\Answer;

class QuizSession
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var int
     */
    private $correctAnswersCount;

    /**
     * @var int
     */
    private $totalAnswersCount;

    /**
     * @var \DateTime
     */
    private $lastRepliedAt;

    /**
     * @var Answer
     */
    private $lastCorrectAnswer;

    public function __construct()
    {
        $this->correctAnswersCount = 0;
        $this->totalAnswersCount = 0;
        $this->lastRepliedAt = new \DateTime();
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     *
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return int
     */
    public function getCorrectAnswersCount()
    {
        return $this->correctAnswersCount;
    }

    /**
     * @param int $correctAnswersCount
     *
     * @return $this
     */
    public function setCorrectAnswersCount($correctAnswersCount)
    {
        $this->correctAnswersCount = $correctAnswersCount;
        return $this;
    }

    /**
     * @return int
     */
    public function getTotalAnswersCount()
    {
        return $this->totalAnswersCount;
    }

    /**
     * @param int $totalAnswersCount
     *
     * @return $this
     */
    public function setTotalAnswersCount($totalAnswersCount)
    {
        $this->totalAnswersCount = $totalAnswersCount;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastRepliedAt()
    {
        return $this->lastRepliedAt;
    }

    /**
     * @param \DateTime $lastRepliedAt
     *
     * @return $this
     */
    public function setLastRepliedAt($lastRepliedAt)
    {
        $this->lastRepliedAt = $lastRepliedAt;
        return $this;
    }

    /**
     * @return Answer
     */
    public function getLastCorrectAnswer()
    {
        return $this->lastCorrectAnswer;
    }

    /**
     * @param Answer $lastCorrectAnswer
     *
     * @return $this
     */
    public function setLastCorrectAnswer($lastCorrectAnswer)
    {
        $this->lastCorrectAnswer = $lastCorrectAnswer;
        return $this;
    }

    public function incrementTotalAnswersCount()
    {
        $this->totalAnswersCount += 1;
        return $this;
    }

    public function incrementCorrectAnswersCount()
    {
        $this->correctAnswersCount += 1;
        return $this;
    }
}
