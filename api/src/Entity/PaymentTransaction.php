<?php

namespace App\Entity;

use App\Repository\PaymentTransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ApiResource(
    normalizationContext: ['groups' => ['paymentTransaction:read']],
    denormalizationContext: ['groups' => ['paymentTransaction:write']],
    collectionOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['paymentTransaction:read']]
        ],
        'post' => [
            'denormalization_context' => ['groups' => ['paymentTransaction:write']]
        ]
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => ['groups' => ['paymentTransaction:read']]
        ],
        'put' => [
            'denormalization_context' => ['groups' => ['paymentTransaction:write']]
        ],
        'delete' => [
            'normalization_context' => ['groups' => ['paymentTransaction:read']]
        ]
    ]
)]
#[ORM\Table(name: "`payment_transaction`")]
#[ORM\Entity(repositoryClass: PaymentTransactionRepository::class)]
class PaymentTransaction
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column (type: "integer")]
    private $id;

    #[Groups(['paymentTransaction:read', 'paymentTransaction:write'])]
    #[ORM\Column(type: "float")]
    private $amount;

    #[ORM\Column(type: "string", length: 255)]
    #[Groups(['paymentTransaction:read', 'paymentTransaction:write'])]
    #[Assert\NotBlank]
    private $status;

    #[ORM\Column(type: "datetime")]
    #[Groups(['paymentTransaction:read'])]
    private $createdAt;

    #[ORM\ManyToOne(inversedBy: 'paymentTransactions')]
    private ?User $buyer = null;

    #[ORM\OneToMany(mappedBy: 'paymentTransaction', targetEntity: Ticketing::class)]
    private Collection $ticketings;

    #[ORM\OneToMany(mappedBy: 'paymentTransaction', targetEntity: RoomReservation::class)]
    private Collection $roomReservations;

    public function __construct()
    {
        $this->ticketings = new ArrayCollection();
        $this->roomReservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
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

    /**
     * @return Collection<int, Ticketing>
     */
    public function getTicketings(): Collection
    {
        return $this->ticketings;
    }

    public function addTicketing(Ticketing $ticketing): self
    {
        if (!$this->ticketings->contains($ticketing)) {
            $this->ticketings->add($ticketing);
            $ticketing->setPaymentTransaction($this);
        }

        return $this;
    }

    public function removeTicketing(Ticketing $ticketing): self
    {
        if ($this->ticketings->removeElement($ticketing)) {
            // set the owning side to null (unless already changed)
            if ($ticketing->getPaymentTransaction() === $this) {
                $ticketing->setPaymentTransaction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RoomReservation>
     */
    public function getRoomReservations(): Collection
    {
        return $this->roomReservations;
    }

    public function addRoomReservation(RoomReservation $roomReservation): self
    {
        if (!$this->roomReservations->contains($roomReservation)) {
            $this->roomReservations->add($roomReservation);
            $roomReservation->setPaymentTransaction($this);
        }

        return $this;
    }

    public function removeRoomReservation(RoomReservation $roomReservation): self
    {
        if ($this->roomReservations->removeElement($roomReservation)) {
            // set the owning side to null (unless already changed)
            if ($roomReservation->getPaymentTransaction() === $this) {
                $roomReservation->setPaymentTransaction(null);
            }
        }

        return $this;
    }
}
