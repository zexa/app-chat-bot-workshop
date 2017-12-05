<?php

namespace Paysera\Workshop\ChatBot\Normalizer;

use Paysera\Component\Serializer\Normalizer\DenormalizerInterface;
use Paysera\Component\Serializer\Normalizer\NormalizerInterface;
use Paysera\Workshop\ChatBot\Entity\Facebook\Message;
use Paysera\Workshop\ChatBot\Entity\Facebook\QuickReply;
use Paysera\Workshop\ChatBot\Entity\Facebook\QuickReplyMessage;

class MessageNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private $quickReplyNormalizer;

    public function __construct(QuickReplyNormalizer $quickReplyNormalizer)
    {
        $this->quickReplyNormalizer = $quickReplyNormalizer;
    }

    /**
     * @param Message $entity
     *
     * @return array
     */
    public function mapFromEntity($entity)
    {
        $data = [
            'text' => $entity->getText(),
        ];

        if ($entity instanceof QuickReplyMessage) {
            foreach ($entity->getReplies() as $reply) {
                $data['quick_replies'][] = $this->quickReplyNormalizer->mapFromEntity($reply);
            }
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return Message
     */
    public function mapToEntity($data)
    {
        $message = new Message();

        if (isset($data['quick_reply'])) {
            $message = new QuickReplyMessage();
            $message->addReply((new QuickReply())->setPayload($data['quick_reply']['payload']));
        }

        $message->setText($data['text']);

        return $message;
    }
}
