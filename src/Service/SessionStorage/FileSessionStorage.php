<?php

namespace Paysera\Workshop\ChatBot\Service\SessionStorage;

use Paysera\Workshop\ChatBot\Entity\Facebook\User;
use Paysera\Workshop\ChatBot\Entity\Quiz\Answer;
use Paysera\Workshop\ChatBot\Entity\QuizSession;

class FileSessionStorage implements SessionStorageInterface
{
    private $storageFile;

    /**
     * @param string $storageFile
     */
    public function __construct($storageFile)
    {
        $this->storageFile = $storageFile;
    }

    /**
     * @param User $user
     * @return QuizSession|null
     */
    public function getQuizSession(User $user)
    {
        $session = null;

        foreach ($this->readFile() as $row) {
            if ($row[0] === $user->getId()) {
                return (new QuizSession())
                    ->setUser($user)
                    ->setTotalAnswersCount((int)$row[1])
                    ->setCorrectAnswersCount((int)$row[2])
                    ->setLastCorrectAnswer((new Answer())->setValue($row[3]))
                    ->setLastRepliedAt(\DateTime::createFromFormat('Y-m-d H:i:s', $row[4]))
                ;
            }
        }

        return $session;
    }

    public function saveQuizSession(QuizSession $session)
    {
        $data = $this->readFile();
        $sessionRow = [
            $session->getUser()->getId(),
            $session->getTotalAnswersCount(),
            $session->getCorrectAnswersCount(),
            $session->getLastCorrectAnswer()->getValue(),
            $session->getLastRepliedAt()->format('Y-m-d H:i:s'),
        ];

        foreach ($data as $key => $row) {
            if ($row[0] === $session->getUser()->getId()) {
                $data[$key] = $sessionRow;
                $this->writeFile($data);
                return true;
            }
        }

        $data[] = $sessionRow;
        $this->writeFile($data);

        return true;
    }

    public function deleteQuizSession(QuizSession $session)
    {
        $data = $this->readFile();
        foreach ($data as $key => $row) {
            if ($row[0] === $session->getUser()->getId()) {
                unset($data[$key]);
                $this->writeFile($data);
                return true;
            }
        }
        $this->writeFile($data);
        return false;
    }

    private function readFile()
    {
        if (!file_exists($this->getFilePath())) {
            return [];
        }

        return array_map('str_getcsv', file($this->getFilePath()));
    }

    private function writeFile(array $data)
    {
        $resource = fopen($this->getFilePath(), 'w');
        foreach ($data as $row) {
            fputcsv($resource, $row);
        }
        fclose($resource);
    }

    private function getFilePath()
    {
        return $this->storageFile;
    }
}
