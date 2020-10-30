<?php

namespace App\Service;

use DateTime;
use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\CharacterRepository;


/**
 * (@inheritdoc)
 */
class CharacterService implements CharacterServiceInterface 
{
    private $characterRepository;
    private $em;

    public function __construct(CharacterRepository $characterRepository ,EntityManagerInterface $em)
    {
        $this->characterRepository = $characterRepository;
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

    public function modify(Character $character)
    {
        $character
            ->setKind('Dadsame')
            ->setName('Anardil')
            ->setSurname('Amie du soleil')
            ->setIntelligence(130)
            ->setLife(11)
            ->setImage('image/anardil.jpg')
            ->setCreation(new \DateTime('now'));

        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }

    public function delete(Character $character)
    {
        $this->em->remove($character);
        $this->em->flush();

        return true;
    }

    /**
     * (@inheritdoc)
     */
    public function getAll()
    {
        $charactersFinal = array();
        $characters = $this->characterRepository->findAll();
        foreach ($characters as $character){
            $charactersFinal[] = $character->toArray();
        }
        return $charactersFinal;
    }
}