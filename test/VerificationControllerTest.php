<?php

namespace Paysera\Test\Workshop\ChatBot;

use Paysera\Workshop\ChatBot\Controller\VerificationController;
use Paysera\Workshop\ChatBot\Hub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class VerificationControllerTest extends TestCase
{
    /**
     * @var VerificationController
     */
    private $verificationController;

    protected function setUp()
    {
        $this->verificationController = new VerificationController('TOKEN');
    }

    public function testVerificationSuccess()
    {
        $request = new Request(
            [],
            [
                Hub::HUB_CHALLENGE => 'challenge',
                Hub::HUB_VERIFY_TOKEN => 'TOKEN',
            ]
        );

        $response = $this->verificationController->verify($request);

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('challenge', $response->getContent());
    }

    /**
     * @dataProvider dataProviderTestVerificationFailure
     * @param Request $request
     */
    public function testVerificationFailure(Request $request)
    {
        $response = $this->verificationController->verify($request);
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }

    public function dataProviderTestVerificationFailure()
    {
        return [
            [
                new Request(),
            ],
            [
                new Request(
                    [],
                    [
                        Hub::HUB_CHALLENGE => 'challenge',
                        Hub::HUB_VERIFY_TOKEN => 'INVALID_TOKEN',
                    ]
                )
            ]
        ];
    }
}
