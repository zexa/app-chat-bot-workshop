<?php

namespace Paysera\Workshop\ChatBot\Service\QuizProvider;

use Paysera\Workshop\ChatBot\Entity\Quiz\Question;
use Paysera\Workshop\ChatBot\Exception\QuizProviderException;

interface QuizProviderInterface
{
    /**
     * @return Question
     * @throws QuizProviderException
     */
    public function getNextQuestion();
}
