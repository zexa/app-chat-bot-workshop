<?php

namespace Paysera\Workshop\ChatBot\Entity\Facebook;

class QuickReplyMessage extends Message
{
    /**
     * @var QuickReply[]
     */
    private $replies;

    public function __construct()
    {
        $this->replies = [];
    }

    /**
     * @return QuickReply[]
     */
    public function getReplies()
    {
        return $this->replies;
    }

    /**
     * @param QuickReply[] $replies
     *
     * @return $this
     */
    public function setReplies($replies)
    {
        $this->replies = $replies;
        return $this;
    }

    /**
     * @param QuickReply $reply
     *
     * @return $this
     */
    public function addReply($reply)
    {
        $this->replies[] = $reply;
        return $this;
    }
}
