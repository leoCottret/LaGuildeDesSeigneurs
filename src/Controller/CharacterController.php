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

//Doc
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use phpDocumentor\Reflection\Types\Integer;

class CharacterController extends AbstractController
{
    private $characterService;

    public function __construct(CharacterServiceInterface $characterService)
    {
        $this->characterService = $characterService;
    }

    //REDIRECT_INDEX
    /**
     * Redirects to index Route
     * 
     * @Route("/character", name="character", methods={"GET","HEAD"})
     * 
     * @OA\Response(
     *      response=302,
     *      description="Redirect",
     * )
     */
    public function redirectIndex(): Response
    {
        return $this->redirectToRoute('character_index');
    }


    //INDEX
    /**
     * Displays available Characters
     * 
     * @Route("/character/index", name="character_index", methods={"GET","HEAD"})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\Schema(
     *          type="array",
     *          @OA\Items(ref=@Model(type=Character::class))
     * 
     *      )
     * )
     * @OA\Response(
     *      response=403,
     *      description="Access Denied",
     * )
     * @OA\Tag(name="Character")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('characterIndex', null);

        $characters = $this->characterService->getAll();

        return new JsonResponse($characters);
    }

    //DISPLAY
    /**
     * Displays the Character
     * 
     * @Route("/character/display/{identifier}",
     * name="character_display",
     * requirements={"identifier": "^([a-z0-9]{40})$"},
     * methods={"GET","HEAD"})
     * @Entity("character", expr="repository.findOneByIdentifier(identifier)")
     * 
     * @OA\Parameter(
     *      name="identifier",
     *      in="path",
     *      description="identifier for the Character",
     *      required=true
     * )
     * @OA\Response(
     *      response=200,
     *      description="Success",
     *      @Model(type=Character::class)
     * )
     * @OA\Response(
     *      response=403,
     *      description="Access denied"
     * )
     * @OA\Response(
     *      response=404,
     *      description="Not Found"
     * )
     * @OA\Tag(name="Character")
     */
    public function display(Character $character)
    {
        $this->denyAccessUnlessGranted('characterDisplay', $character);

        return new JsonResponse($character->toArray());
    }

     //DISPLAY CHARACTERS
    /**
     * Displays the Characters whose intelligence level is greater than or equal to the number in the url
     * 
     * @Route("/character/display/more_intelligent_than/{limit_intelligence}",
     * name="display_more_intelligent_than",
     * requirements={"limit_intelligence"="^([0-9]{1,3})$"},
     * methods={"GET","HEAD"})
     * 
     * @OA\Parameter(
     *      name="limit_intelligence",
     *      in="path",
     *      description="limit bottom of intelligence for the characters than we will display",
     *      required=true
     * )
     * @OA\Response(
     *      response=200,
     *      description="Success",
     *      @Model(type=Character::class)
     * )
     * @OA\Response(
     *      response=403,
     *      description="Access denied"
     * )
     * @OA\Response(
     *      response=404,
     *      description="Not Found"
     * )
     * @OA\Tag(name="Character")
     */
    public function displayMoreIntelligentThan(int $limit_intelligence)
    {
        $this->denyAccessUnlessGranted('characterDisplayMoreIntelligentThan', null);

        $characters = $this->characterService->getCharactersMoreIntelligentThan($limit_intelligence);
        return new JsonResponse($characters);
    }

    //CREATE
    /**
     * Creates the Character
     * 
     * @Route("character/create", name="character_create", methods={"POST","HEAD"})
     * name="character_create"
     * 
     * @OA\Response(
     *      response=200,
     *      description="Success",
     *      @Model(type=Character::class)
     * )
     * @OA\Response(
     *      response=403,
     *      description="Access denied"
     * )
     * @OA\RequestBody(
     *      request="Character",
     *      description="Data for the Character",
     *      required=true,
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(ref="#/components/schemas/Character")
     *      )
     * )
     * @OA\Tag(name="Character")
     */
    public function create(Request $request)
    {
        $this->denyAccessUnlessGranted('characterCreate', null);

        $character = $this->characterService->create($request->getContent());

        return new JsonResponse($character->toArray());
    }

    //Modify
    /**
     * Modify the Character
     * 
     * @Route("/character/modify/{identifier}",
     * name="character_modify",
     * requirements={"identifier": "^([a-z0-9]{40})$"},
     * methods={"PUT","HEAD"})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Success",
     *      @Model(type=Character::class)
     * )
     * @OA\Response(
     *      response=403,
     *      description="Access denied"
     * )
     * @OA\Parameter(
     *      name="identifier",
     *      in="path",
     *      description="identifier for the Character",
     *      required=true
     * )
     * @OA\RequestBody(
     *      request="Character",
     *      description="Data for the Character",
     *      required=true,
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(ref="#/components/schemas/Character")
     *      )
     * )
     * @OA\Tag(name="Character")
     */
    public function modify(Request $request, Character $character)
    {
        $this->denyAccessUnlessGranted('characterModify', $character);

        $character = $this->characterService->modify($character, $request->getContent());
        return new JsonResponse($character->toArray());
    }

    //DELETE
    /**
     * Deletes the Character
     * 
     * @Route("/character/delete/{identifier}",
     * name="character_delete",
     * requirements={"identifier": "^([a-z0-9]{40})$"},
     * methods={"DELETE","HEAD"})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Success",
     *      @OA\Schema(
     *          @OA\Property(property="delete", type="boolean"),
     *      )
     * )
     * @OA\Response(
     *      response=403,
     *      description="Access denied"
     * )
     * @OA\Parameter(
     *      name="identifier",
     *      in="path",
     *      description="identifier for the Character",
     *      required=true
     * )
     * @OA\RequestBody(
     *      request="Character",
     *      description="Data for the Character",
     *      required=true,
     *      @OA\MediaType(
     *          mediaType="application/json",
     *          @OA\Schema(ref="#/components/schemas/Character")
     *      )
     * )
     * @OA\Tag(name="Character")
     * 
     */
    public function delete(Character $character)
    {
        $this->denyAccessUnlessGranted('characterDelete', $character);

        $response = $this->characterService->delete($character);
        return new JsonResponse(array('delete' => $response));
    }
}
