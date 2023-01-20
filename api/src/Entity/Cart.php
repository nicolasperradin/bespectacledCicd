<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
#[ApiResource]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'cart', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Spectator $spectatorId = null;

    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: Ticketing::class)]
    private Collection $ticketId;

    public function __construct()
    {
        $this->ticketId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpectatorId(): ?Spectator
    {
        return $this->spectatorId;
    }

    public function setSpectatorId(Spectator $spectatorId): self
    {
        $this->spectatorId = $spectatorId;

        return $this;
    }

    /**
     * @return Collection<int, Ticketing>
     */
    public function getTicketId(): Collection
    {
        return $this->ticketId;
    }

    public function addTicketId(Ticketing $ticketId): self
    {
        if (!$this->ticketId->contains($ticketId)) {
            $this->ticketId->add($ticketId);
            $ticketId->setCart($this);
        }

        return $this;
    }

    public function removeTicketId(Ticketing $ticketId): self
    {
        if ($this->ticketId->removeElement($ticketId)) {
            // set the owning side to null (unless already changed)
            if ($ticketId->getCart() === $this) {
                $ticketId->setCart(null);
            }
        }

        return $this;
    }
}
