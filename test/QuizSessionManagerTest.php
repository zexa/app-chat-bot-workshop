<?php

namespace Paysera\Test\Workshop\ChatBot;

use org\bovigo\vfs\vfsStream;
use Paysera\Workshop\ChatBot\Entity\Facebook\Message;
use Paysera\Workshop\ChatBot\Entity\Facebook\MessagingRequest;
use Paysera\Workshop\ChatBot\Entity\Facebook\QuickReply;
use Paysera\Workshop\ChatBot\Entity\Facebook\QuickReplyMessage;
use Paysera\Workshop\ChatBot\Entity\Facebook\User;
use Paysera\Workshop\ChatBot\Entity\Quiz\Answer;
use Paysera\Workshop\ChatBot\Entity\QuizMessage;
use Paysera\Workshop\ChatBot\Service\QuizMessageBuilder;
use Paysera\Workshop\ChatBot\Service\QuizSessionManager;
use Paysera\Workshop\ChatBot\Service\SessionStorage\FileSessionStorage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class QuizSessionManagerTest extends TestCase
{
    /**
     * @var FileSessionStorage
     */
    private $fileSessionStorage;

    /**
     * @var QuizSessionManager
     */
    private $quizSessionManager;

    protected function setUp()
    {
        $root = vfsStream::setup('root', null, [
            'session.csv' => sprintf('1,8,8,"ANSWER_2","%s"', (new \DateTime())->format('Y-m-d H:i:s')),
        ]);
        $this->fileSessionStorage = new FileSessionStorage($root->url() . '/session.csv');
        $this->quizSessionManager = new QuizSessionManager(
            $this->fileSessionStorage,
            10,
            $this->getQuizMessageBuilderMock()
        );
    }

    public function testStartQuizSession()
    {
        $messagingRequest = (new MessagingRequest())
            ->setMessage((new Message())->setText('PLAY'))
            ->setSender((new User())->setId('10'))
        ;

        $messagingResponse = $this->quizSessionManager->startQuiz($messagingRequest);
        $quizSession = $this->fileSessionStorage->getQuizSession($messagingRequest->getSender());

        $this->assertNotNull($quizSession);
        $this->assertEquals($messagingRequest->getSender()->getId(), $messagingResponse->getRecipient()->getId());
        $this->assertEquals($messagingResponse->getMessage()->getText(), $this->getQuizMessage()->getMessage()->getText());
    }

    public function testContinueQuiz()
    {
        $messagingRequest = (new MessagingRequest())
            ->setSender((new User())->setId('1'))
            ->setMessage((new QuickReplyMessage())->addReply((new QuickReply())->setPayload('ANSWER_2')))
        ;
        $messagingResponse = $this->quizSessionManager->continueQuiz($messagingRequest);
        $quizSession = $this->fileSessionStorage->getQuizSession($messagingRequest->getSender());

        $this->assertEquals($messagingRequest->getSender()->getId(), $messagingResponse->getRecipient()->getId());
        $this->assertNotNull($quizSession);
        $this->assertInstanceOf(QuickReplyMessage::class, $messagingResponse->getMessage());
        $this->assertEquals(
            $this->getQuizMessage()->getCorrectAnswer()->getValue(),
            $quizSession->getLastCorrectAnswer()->getValue()
        );

        $messagingRequest = (new MessagingRequest())
            ->setSender((new User())->setId('1'))
            ->setMessage(
                (new QuickReplyMessage())
                    ->addReply((new QuickReply())
                        ->setPayload($this->getQuizMessage()->getCorrectAnswer()->getValue())
                    )
            )
        ;
        $messagingResponse = $this->quizSessionManager->continueQuiz($messagingRequest);
        $quizSession = $this->fileSessionStorage->getQuizSession($messagingRequest->getSender());

        $this->assertEquals($messagingRequest->getSender()->getId(), $messagingResponse->getRecipient()->getId());
        $this->assertNull($quizSession);
        $this->assertInstanceOf(Message::class, $messagingResponse->getMessage());
    }

    /**
     * @return MockObject|QuizMessageBuilder
     */
    private function getQuizMessageBuilderMock()
    {
        $quizMessageBuilder = $this->getMockBuilder(QuizMessageBuilder::class)
            ->setMethods(['buildQuizMessage'])
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $quizMessageBuilder
            ->expects($this->any())
            ->method('buildQuizMessage')
            ->willReturn($this->getQuizMessage())
        ;

        return $quizMessageBuilder;
    }

    /**
     * @return QuizMessage
     */
    private function getQuizMessage()
    {
        return (new QuizMessage())
            ->setCorrectAnswer((new Answer())->setValue('Correct'))
            ->setMessage(
                (new QuickReplyMessage())
                    ->setText('Question')
                    ->addReply((new QuickReply())->setPayload('ANSWER_1')->setTitle('Answer 1'))
                    ->addReply((new QuickReply())->setPayload('ANSWER_2')->setTitle('Answer 2'))
            )
        ;
    }
}
