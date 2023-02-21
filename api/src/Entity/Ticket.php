<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Enum\TicketStatusEnum;
use App\Repository\TicketRepository;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TicketRepository::class)]
#[ORM\Table(name: "`ticket`")]
// #[ApiResource(
//     collectionOperations: [
//         'get' => [
//             'normalization_context' => [
//                 'groups' => ['ticket:read']
//             ]
//         ],
//         'post' => [
//             'denormalization_context' => [
//                 'groups' => ['ticket:write']
//             ]
//         ]
//     ],
//     itemOperations: [
//         'get' => [
//             'normalization_context' => [
//                 'groups' => ['ticket:read']
//             ]
//         ],
//         'put' => [
//             'denormalization_context' => [
//                 'groups' => ['ticket:write']
//             ]
//         ]
//     ]
// )]
class Ticket
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ticket:read', 'ticket:write'])]
    #[Assert\NotBlank]
    private ?User $buyer = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ticket:read', 'ticket:write'])]
    #[Assert\NotBlank]
    private ?Event $event = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Groups(['ticket:read', 'ticket:write'])]
    #[Assert\NotBlank]
    private int $status = TicketStatusEnum::PENDING;

    #[ORM\ManyToOne(inversedBy: 'tickets')]
    private ?Transaction $transaction = null;


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

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(TicketStatusEnum $status): self
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
