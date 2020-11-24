<?php

namespace App\Entity;

use App\Repository\PlayerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PlayerRepository::class)
 * @ORM\Table(name="players")
 */
class Player
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 16,
     * )
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=64)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 16,
     * )
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=128)
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $email;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="integer")
     */
    private $mirian = 0;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\Length(
     *      min = 40,
     *      max = 40,
     * )
     */
    private $identifier;

    /**
     * @ORM\Column(type="datetime")
     */
    private $creation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modification;

    /**
     * @ORM\OneToMany(targetEntity=Character::class, mappedBy="player")
     */
    private $characters;

    public function __construct()
    {
        $this->characters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMirian(): ?int
    {
        return $this->mirian;
    }

    public function setMirian(int $mirian): self
    {
        $this->mirian = $mirian;

        return $this;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(string $identifier): self
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getCreation(): ?\DateTimeInterface
    {
        return $this->creation;
    }

    public function setCreation(\DateTimeInterface $creation): self
    {
        $this->creation = $creation;

        return $this;
    }

    public function getModification(): ?\DateTimeInterface
    {
        return $this->modification;
    }

    public function setModification(\DateTimeInterface $modification): self
    {
        $this->modification = $modification;

        return $this;
    }

    // METHODS

    /**
    * Converts the entity in an array
    */
    public function toArray(bool $expand = true)
    { //if expend = null, we add the player but NOT the field characters, so that we avoid an infinite loop
        $player = get_object_vars($this);
        if ($expand && null !== $this->getCharacters()) {
            $characters = array();
            foreach ($this->getCharacters() as $character) {
                $characters[] = $character->toArray(false);// On met ses charact dans un tableau
            }
            $player['characters'] = $characters;// On lui ajoute le tableau
        }

        //Specific data, on garde l'objet dans son format de base jusqu'au bout
        if (null !== $player['creation']) {
            $player['creation'] = $player['creation']->format('Y-m-d H:i:s');
        }

        if (null !== $player['modification']) {
            $player['modification'] = $player['modification']->format('Y-m-d H:i:s');
        }

        return $player;
    }

    /**
     * @return Collection|Character[]
     */
    public function getCharacters(): Collection
    {
        return $this->characters;
    }

    public function addCharacter(Character $character): self
    {
        if (!$this->characters->contains($character)) {
            $this->characters[] = $character;
            $character->setPlayer($this);
        }

        return $this;
    }

    public function removeCharacter(Character $character): self
    {
        if ($this->characters->removeElement($character)) {
            // set the owning side to null (unless already changed)
            if ($character->getPlayer() === $this) {
                $character->setPlayer(null);
            }
        }

        return $this;
    }
}
