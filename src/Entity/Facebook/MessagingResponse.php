<?php

namespace Paysera\Workshop\ChatBot\Entity\Facebook;

class MessagingResponse
{
    const TYPE_RESPONSE = 'RESPONSE';

    /**
     * @var string
     */
    private $type;

    /**
     * @var User
     */
    private $recipient;

    /**
     * @var Message
     */
    private $message;

    public function __construct()
    {
        $this->type = self::TYPE_RESPONSE;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return User
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param User $recipient
     *
     * @return $this
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

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
}
