<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
#[ORM\Table(name: "`room`")]
#[UniqueEntity(fields: ["name"], message: "This room already exists")]
#[ApiResource(
    normalizationContext: ['groups' => ['room:read']],
    denormalizationContext: ['groups' => ['room:write']],
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['room:read']]
        ],
        'post' => [
            'denormalization_context' => ['groups' => ['room:write']]
        ]
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['room:read']]
        ],
        'put' => [
            'denormalization_context' => ['groups' => ['room:write']]
        ],
        'delete' => [
            'denormalization_context' => ['groups' => ['room:write']]
        ]
    ]
)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 55)]
    #[Assert\NotBlank]
    #[Groups(['room:read', 'room:write'])]
    private $name;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups(['room:read', 'room:write'])]
    private ?int $nbSeats = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Groups(['room:read', 'room:write'])]
    private ?float $price = null;

    #[ORM\OneToMany(mappedBy: 'room', targetEntity: Event::class)]
    #[Groups(['room:read'])]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNbSeats(): ?int
    {
        return $this->nbSeats;
    }

    public function setNbSeats(int $nbSeats): self
    {
        $this->nbSeats = $nbSeats;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): self
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setRoom($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getRoom() === $this) {
                $event->setRoom(null);
            }
        }

        return $this;
    }
}
