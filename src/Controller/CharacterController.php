<?php

namespace App\Controller;

use App\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CharacterController extends AbstractController
{

     /**
     * @Route("/character", name="characterIndex", methods={"GET","HEAD"})
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
}
