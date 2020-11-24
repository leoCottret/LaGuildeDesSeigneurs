<?php

namespace App\Service;

use DateTime;
use App\Entity\Player;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\PlayerRepository;

use App\Form\PlayerType;

use LogicException;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

use Symfony\Component\Validator\Validator\ValidatorInterface;// Pas reconnu par PHP Intelephense, c'est normal

/**
 * (@inheritdoc)
 */
class PlayerService implements PlayerServiceInterface
{
    private $playerRepository;
    private $em;
    private $formFactory;
    private $validator;

    public function __construct(
        PlayerRepository $playerRepository,
        EntityManagerInterface $em,
        FormFactoryInterface $formFactory,
        ValidatorInterface $validator
    )
    {
        $this->playerRepository = $playerRepository;
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->validator = $validator;
    }

    public function create(string $data)
    {
        $player = new Player();
        $player
            ->setIdentifier(hash('sha1', uniqid()))
            ->setCreation(new DateTime())
            ->setModification(new DateTime())
        ;

        $this->submit($player, PlayerType::class, $data);
        $this->isEntityFilled($player);

        $this->em->persist($player);
        $this->em->flush();

        return $player;
    }

    public function modify(Player $player, string $data)
    {
        $this->submit($player, PlayerType::class, $data);
        $this->isEntityFilled($player);
        $player->setModification(new DateTime());

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
        foreach ($players as $player) {
            $playersFinal[] = $player->toArray();
        }
        return $playersFinal;
    }

    // End Main Crud Functions
    

    /**
     * {@inheritdoc}
     */
    public function isEntityFilled(Player $player)
    {
        // pour tester la contrainte ci-dessous -> $player->setIdentifier('badIndentifier');

        $errors = $this->validator->validate($player);
        if (count($errors) > 0) {
            throw new UnprocessableEntityHttpException((string)$errors .' Missing data for Entity -> ' . json_encode($player->toArray()));
        }
    }
   
    /**
     * {@inheritdoc}
     */
    public function submit($player, $formName, $data)
    {
        $dataArray = is_array($data) ? $data : json_decode($data, true);

        //Bad array
        if (null !== $data && !is_array($dataArray)) {
            throw new UnprocessableEntityHttpException('Submitted data is not an array -> ' . $data);// Exceptions propre à Symf
        }

        //Submits formformFac
        $form = $this->formFactory->create($formName, $player, ['csrf_protection' => false]);
        $form->submit($dataArray, false);//With false, only submitted fields are validated

        //Gets errors
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            throw new LogicException('Error ' . get_class($error->getCause()) . ' --> ' . $error->getMessageTemplate() . ' ' . json_encode($error->getMessageParameters()));
        }
    }
}
