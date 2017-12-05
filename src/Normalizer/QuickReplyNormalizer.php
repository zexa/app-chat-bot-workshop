<?php

namespace Paysera\Workshop\ChatBot\Normalizer;

use Paysera\Component\Serializer\Normalizer\NormalizerInterface;
use Paysera\Workshop\ChatBot\Entity\Facebook\QuickReply;

class QuickReplyNormalizer implements NormalizerInterface
{
    /**
     * @param QuickReply $entity
     *
     * @return array
     */
    public function mapFromEntity($entity)
    {
        return array_filter([
            'content_type' => $entity->getContentType(),
            'title' => $entity->getTitle(),
            'payload' => $entity->getPayload(),
            'image_url' => $entity->getImageUrl(),
        ]);
    }
}
