<?php

namespace Paysera\Workshop\ChatBot\Service;

use Facebook\Facebook;
use Paysera\Workshop\ChatBot\Entity\Facebook\MessagingResponse;
use Paysera\Workshop\ChatBot\Normalizer\MessagingResponseNormalizer;

class MessageSender
{
    private $fb;
    private $accessToken;
    private $messagingResponseNormalizer;

    /**
     * @param Facebook $fb
     * @param string $accessToken
     * @param MessagingResponseNormalizer $messagingResponseNormalizer
     */
    public function __construct(
        Facebook $fb,
        $accessToken,
        MessagingResponseNormalizer $messagingResponseNormalizer
    ) {
        $this->fb = $fb;
        $this->accessToken = $accessToken;
        $this->messagingResponseNormalizer = $messagingResponseNormalizer;
    }

    public function sendMessagingResponse(MessagingResponse $messagingResponse)
    {
        return $this->fb->post(
            '/me/messages',
            $this->messagingResponseNormalizer->mapFromEntity($messagingResponse),
            $this->accessToken
        );
    }
}
