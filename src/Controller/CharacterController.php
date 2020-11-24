<?php

namespace App\Controller;

use App\Entity\Character;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CharacterServiceInterface;

use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;

class CharacterController extends AbstractController
{
    private $characterService;

    public function __construct(CharacterServiceInterface $characterService)
    {
        $this->characterService = $characterService;
    }

    /**
    * @Route("/character", name="character", methods={"GET","HEAD"})
    */
    public function redirectIndex(): Response
    {
        return $this->redirectToRoute('character_index');
    }


    /**
    * @Route("/character/index", name="character_index", methods={"GET","HEAD"})
    */
    public function index()
    {
        $this->denyAccessUnlessGranted('characterIndex', null);

        $characters = $this->characterService->getAll();

        return new JsonResponse($characters);
    }

    /**
     * @Route("/character/display/{identifier}",
     * name="character_display",
     * requirements={"identifier": "^([a-z0-9]{40})$"},
     * methods={"GET","HEAD"})
     * @Entity("character", expr="repository.findOneByIdentifier(identifier)")
     */
    public function display(Character $character)
    {
        $this->denyAccessUnlessGranted('characterDisplay', $character);

        return new JsonResponse($character->toArray());
    }

    /**
    * @Route("/character/modify/{identifier}",
    * name="character_modify",
    * requirements={"identifier": "^([a-z0-9]{40})$"},
    * methods={"PUT","HEAD"})
    */
    public function modify(Request $request, Character $character)
    {
        $this->denyAccessUnlessGranted('characterModify', $character);

        $character = $this->characterService->modify($character, $request->getContent());
        return new JsonResponse($character->toArray());
    }

    /**
    * @Route("/character/delete/{identifier}",
    * name="character_delete",
    * requirements={"identifier": "^([a-z0-9]{40})$"},
    * methods={"DELETE","HEAD"})
    */
    public function delete(Character $character)
    {
        $this->denyAccessUnlessGranted('characterDelete', $character);

        $response = $this->characterService->delete($character);
        return new JsonResponse(array('delete' => $response));
    }

    /**
     * @Route("character/create", name="character_create", methods={"POST","HEAD"})
     * name="character_create"
     */
    public function create(Request $request)
    {
        $this->denyAccessUnlessGranted('characterCreate', null);

        $character = $this->characterService->create($request->getContent());

        return new JsonResponse($character->toArray());
    }
}
