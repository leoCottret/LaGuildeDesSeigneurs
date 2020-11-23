<?php

namespace App\Service;

use DateTime;
use App\Entity\Character;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\CharacterRepository;

use App\Form\CharacterType;

use LogicException;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;


/**
 * (@inheritdoc)
 */
class CharacterService implements CharacterServiceInterface 
{
    private $characterRepository;
    private $em;
    private $formFactory;

    public function __construct(CharacterRepository $characterRepository, EntityManagerInterface $em, FormFactoryInterface $formFactory)
    {
        $this->characterRepository = $characterRepository;
        $this->formFactory = $formFactory;
        $this->em = $em;
    }
    
    public function create(string $data)
    {
        //Use with {"kind":"Dame","name":"Eldalótë","surname":"Fleur elfique","caste":"Elfe","knowledge":"Arts","intelligence":120,"life":12,"image":"/images/eldalote.jpg"}
        $character = new Character();
        $character
            ->setIdentifier(hash('sha1', uniqid()))
            ->setCreation(new DateTime())
            ->setModification(new DateTime())
        ;
        $this->submit($character, CharacterType::class, $data);
        $this->isEntityFilled($character);

        $this->em->persist($character);
        $this->em->flush();

        return $character;
    }

    public function modify(Character $character, string $data)
    {
        $this->submit($character, CharacterType::class, $data);
        $this->isEntityFilled($character);
        $character->setModification(new DateTime());

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


    // End Main Crud Functions
    

    /**
     * {@inheritdoc}
     */
    public function isEntityFilled(Character $character)
    {
        if (null === $character->getKind() ||
            null === $character->getName() ||
            null === $character->getSurname() ||
            null === $character->getIdentifier() ||
            null === $character->getCreation() ||
            null === $character->getModification()) {
            throw new UnprocessableEntityHttpException('Missing data for Entity -> ' . json_encode($character->toArray()));
        }
    }
   
    /**
     * {@inheritdoc}
     */
    public function submit($character, $formName, $data)
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true);

        //Bad array
        if (null !== $data && !is_array($dataArray)) {
            throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);// Exceptions propre à Symf
        }

        //Submits formformFac
        $form = $this->formFactory->create($formName, $character, ['csrf_protection' => false]);
        $form->submit($dataArray, false);//With false, only submitted fields are validated

        //Gets errors
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            throw new LogicException('Error ' . get_class($error->getCause()) . ' --> ' . $error->getMessageTemplate() . ' ' . json_encode($error->getMessageParameters()));
        }
    }
}