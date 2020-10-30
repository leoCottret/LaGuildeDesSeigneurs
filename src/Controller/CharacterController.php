<?php

namespace App\Controller;

use App\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CharacterServiceInterface;

class CharacterController extends AbstractController
{
    private $characterService;

    public function __construct(CharacterServiceInterface $characterService)
    {
        $this->characterService = $characterService;
    }

     /**
     * @Route("/character", name="character_index", methods={"GET","HEAD"})
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CharacterController.php',
        ]);
    }

    /**
     * @Route("/display", name="characterDisplay", methods={"GET","HEAD"})
     * name="character_display"
     */
    public function display()
    {
        $character = new Character();
        return new JsonResponse($character->toArray());
    }

    /**
     * @Route("/create", name="characterDisplay", methods={"POST","HEAD"})
     * name="character_create"
     */
    public function create()
    {
        $character = $this->characterService->create();
        return new JsonResponse($character->toArray());
    }
}
