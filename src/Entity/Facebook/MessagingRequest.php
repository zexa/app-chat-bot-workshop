<?php

namespace Paysera\Workshop\ChatBot\Entity\Facebook;

class MessagingRequest
{
    /**
     * @var Message
     */
    private $message;

    /**
     * @var User
     */
    private $sender;

    /**
     * @return Message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param Message $message
     *
     * @return $this
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return User
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @param User $sender
     *
     * @return $this
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
        return $this;
    }
}
