<?php

namespace App\Service;

use DateTime;
use App\Entity\Character;


/**
 * (@inheritdoc)
 */
class CharacterService implements CharacterServiceInterface {

    public function create()
    {
        $character = new Character();
        $character
            ->setKind('Dame')
            ->setName('Anardil')
            ->setSurname('Amie du soleil')
            ->setIntelligence(130)
            ->setLife(11)
            ->setImage('image/anardil.jpg')
            ->setCreation(new \DateTime('now'));

        return $character;
    }
}