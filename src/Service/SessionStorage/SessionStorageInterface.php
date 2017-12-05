<?php

namespace Paysera\Workshop\ChatBot\Service\SessionStorage;

use Paysera\Workshop\ChatBot\Entity\Facebook\User;
use Paysera\Workshop\ChatBot\Entity\QuizSession;

interface SessionStorageInterface
{
    /**
     * @param User $user
     * @return QuizSession|null
     */
    public function getQuizSession(User $user);

    /**
     * @param QuizSession $session
     * @return bool
     */
    public function saveQuizSession(QuizSession $session);

    /**
     * @param QuizSession $session
     * @return bool
     */
    public function deleteQuizSession(QuizSession $session);
}
