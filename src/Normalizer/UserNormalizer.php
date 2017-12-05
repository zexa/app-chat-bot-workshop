<?php

namespace Paysera\Workshop\ChatBot\Normalizer;

use Paysera\Component\Serializer\Normalizer\DenormalizerInterface;
use Paysera\Component\Serializer\Normalizer\NormalizerInterface;
use Paysera\Workshop\ChatBot\Entity\Facebook\User;

class UserNormalizer implements NormalizerInterface, DenormalizerInterface
{
    /**
     * @param User $entity
     *
     * @return array
     */
    public function mapFromEntity($entity)
    {
        return ['id' => $entity->getId()];
    }

    /**
     * @param array $data
     *
     * @return User
     */
    public function mapToEntity($data)
    {
        return (new User())->setId($data['id']);
    }
}
