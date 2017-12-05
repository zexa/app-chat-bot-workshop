<?php

namespace Paysera\Workshop\ChatBot\Controller;

use Paysera\Workshop\ChatBot\Hub;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificationController
{
    private $verifyToken;

    public function __construct($verifyToken)
    {
        $this->verifyToken = $verifyToken;
    }

    public function verify(Request $request)
    {
        $challenge = $request->get(Hub::HUB_CHALLENGE);
        $verifyToken = $request->get(Hub::HUB_VERIFY_TOKEN);

        if ($challenge === null || $verifyToken === null) {
            return new Response('Missing Challenge or VerifyToken', Response::HTTP_BAD_REQUEST);
        }

        if ($verifyToken !== $this->verifyToken) {
            return new Response('VerifyToken is invalid', Response::HTTP_BAD_REQUEST);
        }

        return new Response($challenge);
    }
}
