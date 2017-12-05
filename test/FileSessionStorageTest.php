<?php

namespace Paysera\Test\Workshop\ChatBot;

use org\bovigo\vfs\vfsStream;
use Paysera\Workshop\ChatBot\Entity\Facebook\User;
use Paysera\Workshop\ChatBot\Entity\Quiz\Answer;
use Paysera\Workshop\ChatBot\Entity\QuizSession;
use Paysera\Workshop\ChatBot\Service\SessionStorage\FileSessionStorage;
use PHPUnit\Framework\TestCase;

class FileSessionStorageTest extends TestCase
{
    /**
     * @var FileSessionStorage
     */
    private $fileSessionStorage;

    protected function setUp()
    {
        $root = vfsStream::setup();
        $this->fileSessionStorage = new FileSessionStorage($root->url() . '/session.csv');
    }

    /**
     * @dataProvider dataProviderTestSessionCRUDOperations
     * @param QuizSession $quizSession
     */
    public function testSessionCRUDOperations(QuizSession $quizSession)
    {
        $this->fileSessionStorage->saveQuizSession($quizSession);
        $savedQuizSession = $this->fileSessionStorage->getQuizSession($quizSession->getUser());

        $this->assertNotNull($savedQuizSession);
        $this->assertEquals($quizSession->getUser()->getId(), $savedQuizSession->getUser()->getId());
        $this->assertEquals($quizSession->getTotalAnswersCount(), $savedQuizSession->getTotalAnswersCount());
        $this->assertEquals($quizSession->getCorrectAnswersCount(), $savedQuizSession->getCorrectAnswersCount());
        $this->assertEquals($quizSession->getLastRepliedAt(), $savedQuizSession->getLastRepliedAt());
        $this->assertEquals(
            $quizSession->getLastCorrectAnswer()->getValue(),
            $savedQuizSession->getLastCorrectAnswer()->getValue()
        );

        $this->fileSessionStorage->deleteQuizSession($savedQuizSession);
        $this->assertNull($this->fileSessionStorage->getQuizSession($savedQuizSession->getUser()));
    }

    public function dataProviderTestSessionCRUDOperations()
    {
        return [
            [
                (new QuizSession())
                    ->setUser((new User())->setId('1'))
                    ->setLastCorrectAnswer((new Answer())->setValue('Answer 1'))
                    ->setTotalAnswersCount(10)
                    ->setCorrectAnswersCount(8)
                    ->setLastRepliedAt(new \DateTime('-30 minutes'))
            ],
            [
                (new QuizSession())
                    ->setUser((new User())->setId('2'))
                    ->setLastCorrectAnswer((new Answer())->setValue('Answer 2'))
                    ->setTotalAnswersCount(9)
                    ->setCorrectAnswersCount(7)
                    ->setLastRepliedAt(new \DateTime('-10 minutes'))
            ]
        ];
    }
}
