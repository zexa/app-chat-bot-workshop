<?php

namespace Paysera\Workshop\ChatBot\Service;

use Paysera\Workshop\ChatBot\Normalizer\MessagingRequestNormalizer;
use Symfony\Component\HttpFoundation\Request;

class MessagingExtractor
{
    private $messagingRequestNormalizer;

    public function __construct(MessagingRequestNormalizer $messagingRequestNormalizer)
    {
        $this->messagingRequestNormalizer = $messagingRequestNormalizer;
    }

    public function extractMessagingRequest(Request $request)
    {
        $content = json_decode($request->getContent(), true);
        return $this->messagingRequestNormalizer->mapToEntity($content['entry'][0]['messaging'][0]);
    }
}
