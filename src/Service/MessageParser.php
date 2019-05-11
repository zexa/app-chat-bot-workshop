<?php

namespace Service;

use Service\TriviaProvider;

class MessageParser {

  private $sender;
  private $message;
  private $response;
  private $rootDir;

  public function __construct($sender, $message, $rootDir) {
    $this->sender = $sender;
    $this->message = $message; 
    $this->rootDir = $rootDir;
    $this->response = [
      'messaging_type' => 'RESPONSE',
      'recipient' => [
          'id' => $sender,
      ],
      'message' => [
          'text' => "I don't recognize that command, sorry. :/",
      ]
    ];
  }

  public function parse() {
    if (substr($this->message, 0, 1) === '!') {
      // without ! at the start
      $fullCommand = substr($this->message, 1, strlen($this->message));
      
      // ar komanda turi argumentu?
      // t.y., jeigu yra tarpu, ar po pirmo tarpo lieka teksto
      $firstSpace = strpos($fullCommand, ' ');
      if ($firstSpace !== false && $firstSpace <= strlen($fullCommand)) {
        $command = substr($fullCommand, 0, $firstSpace);
        $arguments = substr($fullCommand, $firstSpace, strlen($fullCommand));
      } else {
        $command = $fullCommand;
        $arguments = '';
      }

      switch ($command) {
        case 'debug': 
          $this->response['message']['text'] = $arguments;
          break;
        case 'hello':
          $this->response['message']['text'] =
            'Hi ' . $this->sender . " :)";
          break;
        case 'trivia':
          $tp = new TriviaProvider($this->sender, $this->rootDir);
          $this->response['message']['text'] = $tp->askTrivia();
          break;
        case 'answer':
          $tp = new TriviaProvider($this->sender, $this->rootDir);
          $this->response['message']['text'] = $tp->answerTrivia($arguments);
          break;
        case 'score':
          $this->response['message']['text'] = 'UNDER CONSTRUCTION';
          break;
      }
    }
    return $this->response;
  }
}

?>
