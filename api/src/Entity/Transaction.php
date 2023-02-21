<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\TransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


// #[ApiResource(
//     normalizationContext: ['groups' => ['transaction:read']],
//     denormalizationContext: ['groups' => ['transaction:write']],
//     collectionOperations: [
//         'get' => [
//             'normalization_context' => ['groups' => ['transaction:read']]
//         ],
//         'post' => [
//             'denormalization_context' => ['groups' => ['transaction:write']]
//         ]
//     ],
//     itemOperations: [
//         'get' => [
//             'normalization_context' => ['groups' => ['transaction:read']]
//         ],
//         'put' => [
//             'denormalization_context' => ['groups' => ['transaction:write']]
//         ],
//         'delete' => [
//             'normalization_context' => ['groups' => ['transaction:read']]
//         ]
//     ]
// )]
#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column (type: "integer")]
    private $id;

    #[Groups(['transaction:read', 'transaction:write'])]
    #[ORM\Column(type: "float")]
    private $amount;

    #[ORM\Column(type: "string", length: 255)]
    #[Groups(['transaction:read', 'transaction:write'])]
    #[Assert\NotBlank]
    private $status;

    #[ORM\Column(type: "datetime")]
    #[Groups(['transaction:read'])]
    private $createdAt;

    #[ORM\ManyToOne(inversedBy: 'transactions')]
    private ?User $buyer = null;

    #[ORM\OneToMany(mappedBy: 'transaction', targetEntity: Ticket::class)]
    private Collection $tickets;

    #[ORM\OneToMany(mappedBy: 'transaction', targetEntity: Booking::class)]
    private Collection $bookings;

    public function __construct()
    {
        $this->tickets = new ArrayCollection();
        $this->bookings = new ArrayCollection();
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
     * @return Collection<int, Ticket>
     */
    public function getTickets(): Collection
    {
        return $this->tickets;
    }

    public function addTicket(Ticket $ticket): self
    {
        if (!$this->tickets->contains($ticket)) {
            $this->tickets->add($ticket);
            $ticket->setTransaction($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getTransaction() === $this) {
                $ticket->setTransaction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Booking>
     */
    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): self
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setTransaction($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getTransaction() === $this) {
                $booking->setTransaction(null);
            }
        }

        return $this;
    }
}
