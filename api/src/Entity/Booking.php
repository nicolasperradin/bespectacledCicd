<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookingRepository;
use App\Enum\BookingStatusEnum;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
// #[ApiResource(
//     collectionOperations: [
//         'get' => [
//             'normalization_context' => [
//                 'groups' => ['booking:read']
//             ]
//         ],
//         'post' => [
//             'denormalization_context' => [
//                 'groups' => ['booking:write']
//             ]
//         ]
//     ],
//     itemOperations: [
//         'get' => [
//             'normalization_context' => [
//                 'groups' => ['booking:read']
//             ]
//         ],
//         'put' => [
//             'denormalization_context' => [
//                 'groups' => ['booking:write']
//             ]
//         ]
//     ]
// )]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['booking:read', 'booking:write'])]
    private ?\DateTimeInterface $created = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['booking:read', 'booking:write'])]
    #[Assert\NotBlank]
    private ?Venue $venueId = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['booking:read', 'booking:write'])]
    #[Assert\NotBlank]
    private ?User $client = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['booking:read', 'booking:write'])]
    #[Assert\NotBlank]
    private int $status = BookingStatusEnum::PENDING;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['booking:read', 'booking:write'])]
    private ?Transaction $transaction = null;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getVenueId(): ?Venue
    {
        return $this->venueId;
    }

    public function setVenueId(?Venue $venueId): self
    {
        $this->venueId = $venueId;

        return $this;
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(BookingStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(?Transaction $transaction): self
    {
        $this->transaction = $transaction;

        return $this;
    }
    
}
