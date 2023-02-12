<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['event:read']
            ]
        ],
        'post' => [
            'denormalization_context' => [
                'groups' => ['event:write']
            ]
        ]
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['event:read']
            ]
        ],
        'put' => [
            'denormalization_context' => [
                'groups' => ['event:write']
            ]
        ]
    ]
)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['event:read', 'event:write'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['event:read', 'event:write'])]
    private ?Room $room = null;

    #[ORM\ManyToMany(targetEntity: Artist::class, inversedBy: 'events')]
    #[ORM\JoinTable(name: 'event_artist')]
    #[Groups(['event:read', 'event:write'])]
    private Collection $artists;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Groups(['event:read', 'event:write'])]
    private ?int $price = null;

    public function __construct()
    {
        $this->artists = new ArrayCollection();
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

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return Collection<int, Artist>
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(Artist $artist): self
    {
        if (!$this->artists->contains($artist)) {
            $this->artists->add($artist);
        }

        return $this;
    }

    public function removeArtist(Artist $artist): self
    {
        $this->artists->removeElement($artist);

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
