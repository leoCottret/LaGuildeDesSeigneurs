<?php

namespace App\Service;

use DateTime;
use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;


/**
 * (@inheritdoc)
 */
class CharacterService implements CharacterServiceInterface 
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

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
            ->setCreation(new \DateTime('now'))
            ->setIdentifier(hash('sha1', uniqid()));

        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }
}