<?php

namespace Service;

use GuzzleHttp\Client;

class TriviaProvider {
  private $userID;
  private $client;
  private $filePath;

  public function __construct($userID, $rootDir) {
    $this->userID = $userID;
    // I'm aware that we this is not a smart place to store my file location.
    // Please advise on better practices.
    $this->filePath = $rootDir . '/trivia-session.json';
    $this->client = new Client([
      'timeout' => 5.0,
    ]);
  }

  private function requestTrivia() {
    $response = $this->client->GET('https://opentdb.com/api.php?difficulty=easy&amount=1');
    $responseBody = $response->getBody();
    $responseContents = $responseBody->getContents();
    $responseDecoded = json_decode($responseContents, true);
    return $responseDecoded['results'][0];
  }

  private function loadAllTriviaSessions() {
    return json_decode(file_get_contents($this->filePath), true);
  }

  private function loadTriviaSession() {
    $allTriviaSessions = $this->loadAllTriviaSessions();
    return $allTriviaSessions[$this->userID];
  }

  // rebuild the whole sessions .json file as
  // I'm not aware how to do it in a better way.
  private function saveTriviaSession($trivia) {
    // Joins and enumerates the incorrect and correct answers
    // for easier answer selection.
    $ta = $trivia['incorrect_answers'];
    $ta[] = $trivia['correct_answer'];
    shuffle($ta);
    $trivia['corrent_answer_int'] = array_search(
      $trivia['correct_answer'], 
      $ta
    );
    for ($i = 0; $i < count($ta); $i++) {
      $ta[$i] = $i . '. ' . $ta[$i];
    }
    $trivia['combined_answers'] = $ta;

    $allTriviaSessions = $this->loadAllTriviaSessions();
    $allTriviaSessions[$this->userID] = $trivia;
    $sessionsEncoded = json_encode($allTriviaSessions);
    file_put_contents($this->filePath, $sessionsEncoded); 
    return $trivia;
  }

  private function deleteTriviaSession() {
    $allTriviaSessions = $this->loadAllTriviaSessions();
    $allTriviaSessions[$this->userID] = NULL;
    $sessionsEncoded = json_encode($allTriviaSessions);
    file_put_contents($this->filePath, $sessionsEncoded);
  }

  public function askTrivia() {
    $trivia = $this->loadTriviaSession();
    if ($trivia=== NULL) {
      $trivia= $this->saveTriviaSession($this->requestTrivia());
    }
    // Trivia question
    $tq = 'Question: ' . $trivia['question'] . "\n";
    // Trivia Answers
    $ta = "Answers:\n" . implode("\n", $trivia['combined_answers']);

    return $tq . $ta;
  }

  public function answerTrivia($answerInt) {
    $answerInt = intval($answerInt);
    $triviaSession = $this->loadTriviaSession();
    if ($triviaSession === NULL) {
      return 'Please ask a trivia question with !question first';
    }
    if ($answerInt === $triviaSession['corrent_answer_int']) {
      $this->deleteTriviaSession();
      return 'Correct answer! :)';
    } else {
      return "Incorrect answer. :'(";
    } 
  }
}

?>
