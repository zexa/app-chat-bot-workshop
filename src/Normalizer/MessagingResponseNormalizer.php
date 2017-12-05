<?php

namespace Paysera\Workshop\ChatBot\Normalizer;

use Paysera\Component\Serializer\Normalizer\NormalizerInterface;
use Paysera\Workshop\ChatBot\Entity\Facebook\MessagingResponse;

class MessagingResponseNormalizer implements NormalizerInterface
{
    private $messageNormalizer;
    private $userNormalizer;

    public function __construct(
        MessageNormalizer $messageNormalizer,
        UserNormalizer $userNormalizer
    ) {
        $this->messageNormalizer = $messageNormalizer;
        $this->userNormalizer = $userNormalizer;
    }

    /**
     * @param MessagingResponse $entity
     *
     * @return array
     */
    public function mapFromEntity($entity)
    {
        return [
            'messaging_type' => $entity->getType(),
            'recipient' => $this->userNormalizer->mapFromEntity($entity->getRecipient()),
            'message' => $this->messageNormalizer->mapFromEntity($entity->getMessage())
        ];
    }
}
