<?php

namespace Paysera\Workshop\ChatBot\Entity\Quiz;

class Question
{
    /**
     * @var string
     */
    private $description;

    /**
     * @var Answer
     */
    private $correctAnswer;

    /**
     * @var Answer[]
     */
    private $incorrectAnswers;

    public function __construct()
    {
        $this->incorrectAnswers = [];
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
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

    /**
     * @return Answer[]
     */
    public function getIncorrectAnswers()
    {
        return $this->incorrectAnswers;
    }

    /**
     * @param Answer[] $incorrectAnswers
     *
     * @return $this
     */
    public function setIncorrectAnswers($incorrectAnswers)
    {
        $this->incorrectAnswers = $incorrectAnswers;
        return $this;
    }

    /**
     * @param Answer $incorrectAnswer
     * @return $this
     */
    public function addIncorrectAnswer($incorrectAnswer)
    {
        $this->incorrectAnswers[] = $incorrectAnswer;
        return $this;
    }
}
