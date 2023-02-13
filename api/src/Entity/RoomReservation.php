<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\RoomReservationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\RoomReservationStatusEnum;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RoomReservationRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['roomReservation:read']
            ]
        ],
        'post' => [
            'denormalization_context' => [
                'groups' => ['roomReservation:write']
            ]
        ]
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['roomReservation:read']
            ]
        ],
        'put' => [
            'denormalization_context' => [
                'groups' => ['roomReservation:write']
            ]
        ]
    ]
)]
class RoomReservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['roomReservation:read', 'roomReservation:write'])]
    private ?\DateTimeInterface $created = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['roomReservation:read', 'roomReservation:write'])]
    #[Assert\NotBlank]
    private ?Room $roomId = null;

    #[ORM\ManyToOne(inversedBy: 'roomReservations')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['roomReservation:read', 'roomReservation:write'])]
    #[Assert\NotBlank]
    private ?User $client = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['roomReservation:read', 'roomReservation:write'])]
    #[Assert\NotBlank]
    private int $status = RoomReservationStatusEnum::PENDING;

    #[ORM\ManyToOne(inversedBy: 'roomReservations')]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['roomReservation:read', 'roomReservation:write'])]
    private ?PaymentTransaction $paymentTransaction = null;
    

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

    public function getRoomId(): ?Room
    {
        return $this->roomId;
    }

    public function setRoomId(?Room $roomId): self
    {
        $this->roomId = $roomId;

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

    public function getStatus(): RoomReservationStatusEnum
    {
        return $this->status;
    }

    public function setStatus(RoomReservationStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPaymentTransaction(): ?PaymentTransaction
    {
        return $this->paymentTransaction;
    }

    public function setPaymentTransaction(?PaymentTransaction $paymentTransaction): self
    {
        $this->paymentTransaction = $paymentTransaction;

        return $this;
    }
    
}
