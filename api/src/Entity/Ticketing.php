<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\TicketingRepository;
use Doctrine\ORM\Mapping as ORM;
use Enum\TicketingStatusEnum;

#[ORM\Entity(repositoryClass: TicketingRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['ticketing:read']
            ]
        ],
        'post' => [
            'denormalization_context' => [
                'groups' => ['ticketing:write']
            ]
        ]
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['ticketing:read']
            ]
        ],
        'put' => [
            'denormalization_context' => [
                'groups' => ['ticketing:write']
            ]
        ]
    ]
)]
class Ticketing
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ticketings')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ticketing:read', 'ticketing:write'])]
    private ?User $buyer = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ticketing:read', 'ticketing:write'])]
    private ?Event $event = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['ticketing:read', 'ticketing:write'])]
    private TicketingStatusEnum $status = TicketingStatusEnum::PENDING;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBuyer(): ?User
    {
        return $this->buyer;
    }

    public function setBuyer(?User $buyer): self
    {
        $this->buyer = $buyer;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getStatus(): TicketingStatusEnum
    {
        return $this->status;
    }

    public function setStatus(TicketingStatusEnum $status): self
    {
        $this->status = $status;

        return $this;
    }

}
