<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ShowRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShowRepository::class)]
#[ORM\Table(name: '`show`')]
#[ApiResource]
class Show
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'shows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Room $roomId = null;

    #[ORM\ManyToOne(inversedBy: 'shows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Artist $artistId = null;

    #[ORM\Column]
    private ?int $remainingPlace = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRoomId(): ?Room
    {
        return $this->roomId;
    }

    public function setRoomId(?Room $roomId): self
    {
        $this->roomId = $roomId;

        return $this;
    }

    public function getArtistId(): ?Artist
    {
        return $this->artistId;
    }

    public function setArtistId(?Artist $artistId): self
    {
        $this->artistId = $artistId;

        return $this;
    }

    public function getRemainingPlace(): ?int
    {
        return $this->remainingPlace;
    }

    public function setRemainingPlace(int $remainingPlace): self
    {
        $this->remainingPlace = $remainingPlace;

        return $this;
    }
}
