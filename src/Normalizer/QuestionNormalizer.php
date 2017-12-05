<?php

namespace Paysera\Workshop\ChatBot\Normalizer;

use Paysera\Component\Serializer\Normalizer\DenormalizerInterface;
use Paysera\Workshop\ChatBot\Entity\Quiz\Question;

class QuestionNormalizer implements DenormalizerInterface
{
    private $answerNormalizer;

    public function __construct(AnswerNormalizer $answerNormalizer)
    {
        $this->answerNormalizer = $answerNormalizer;
    }

    /**
     * @param array $data
     *
     * @return Question
     */
    public function mapToEntity($data)
    {
        $question = new Question();

        $question
            ->setDescription(html_entity_decode($data['question'], ENT_QUOTES))
            ->setCorrectAnswer($this->answerNormalizer->mapToEntity($data['correct_answer']))
        ;
        foreach ($data['incorrect_answers'] as $incorrectAnswer) {
            $question->addIncorrectAnswer($this->answerNormalizer->mapToEntity($incorrectAnswer));
        }

        return $question;
    }
}
