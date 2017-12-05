<?php

namespace Paysera\Workshop\ChatBot\Normalizer;

use Paysera\Component\Serializer\Normalizer\DenormalizerInterface;
use Paysera\Workshop\ChatBot\Entity\Facebook\MessagingRequest;

class MessagingRequestNormalizer implements DenormalizerInterface
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
     * @param array $data
     *
     * @return MessagingRequest
     */
    public function mapToEntity($data)
    {
        return (new MessagingRequest())
            ->setMessage($this->messageNormalizer->mapToEntity($data['message']))
            ->setSender($this->userNormalizer->mapToEntity($data['sender']))
        ;
    }
}
