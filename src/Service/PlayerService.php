<?php

namespace App\Service;

use DateTime;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\PlayerRepository;


/**
 * (@inheritdoc)
 */
class PlayerService implements PlayerServiceInterface 
{
    private $playerRepository;
    private $em;

    public function __construct(PlayerRepository $playerRepository, EntityManagerInterface $em)
    {
        $this->playerRepository = $playerRepository;
        $this->em = $em;
    }

    public function create()
    {
        $player = new Player();
        $player
            ->setFirstname('Léo')
            ->setLastname('COTTRET')
            ->setEmail('leocottret@gmail.com')
            ->setCreation(new DateTime('now'))
            ->setModification(new DateTime('now'))
            ->setIdentifier(hash('sha1', uniqid()));

        $this->em->persist($player);
        $this->em->flush();

        return $player;
    }

    public function modify(Player $player)
    {
        $player
            ->setFirstname('LéoPut')
            ->setLastname('COTTRETPut')
            ->setEmail('leocottretput@gmail.com')
            ->setModification(new DateTime('now'));

        $this->em->persist($player);
        $this->em->flush();

        return $player;
    }

    public function delete(Player $player)
    {
        $this->em->remove($player);
        $this->em->flush();

        return true;
    }

    /**
     * (@inheritdoc)
     */
    public function getAll()
    {
        $playersFinal = array();
        $players = $this->playerRepository->findAll();
        foreach ($players as $player){
            $playersFinal[] = $player->toArray();
        }
        return $playersFinal;
    }
}