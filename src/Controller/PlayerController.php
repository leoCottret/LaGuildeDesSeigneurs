<?php

namespace App\Controller;

use App\Entity\Player;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PlayerServiceInterface;

class PlayerController extends AbstractController
{
    private $playerService;

    public function __construct(PlayerServiceInterface $playerService)
    {
        $this->playerService = $playerService;
    }

    /**
     * @Route("player/create", name="player_create", methods={"POST","HEAD"})
     * name="player_create"
     */
    public function create()
    {
        $this->denyAccessUnlessGranted('playerCreate', null);

        $player = $this->playerService->create();

        return new JsonResponse($player->toArray());
    }
}
