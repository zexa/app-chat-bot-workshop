<?php

namespace Paysera\Workshop\ChatBot\Normalizer;

use Paysera\Component\Serializer\Normalizer\DenormalizerInterface;
use Paysera\Workshop\ChatBot\Entity\Quiz\Answer;

class AnswerNormalizer implements DenormalizerInterface
{
    /**
     * @param string $data
     *
     * @return Answer
     */
    public function mapToEntity($data)
    {
        return (new Answer())->setValue(html_entity_decode($data, ENT_QUOTES));
    }
}
