<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ArtistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ArtistRepository::class)]
#[ORM\Table(name: "`artist`")]
#[ApiResource(
    normalizationContext: ["groups" => ["artist:get"]],
    denormalizationContext: ["groups" => ["artist:post"]],
    collectionOperations: [
        "get" => [
            "pagination_enabled" => false,
            "access_control" => "is_granted('ROLE_ADMIN')",
            "normalization_context" => ["groups" => ["artist:get"]]
        ],
        "post" => [
            "pagination_enabled" => false,
            "denormalization_context" => ["groups" => ["artist:post"]]
        ]
    ],
    validationGroups: ["artist:get", "artist:post", "artist:put"],
    itemOperations: [
        "get" => [
            "normalization_context" => [
                "groups" => ["artist:get"],
                "access_control" => "is_granted('IS_AUTHENTICATED_FULLY') and object == user  or is_granted('ROLE_ADMIN')"
            ]
        ],
        "put" => [
            "denormalization_context" => ["groups" => ["artist:put"]],
            "normalization_context" => ["groups" => ["artist:get"]],
            "access_control" => "is_granted('IS_AUTHENTICATED_FULLY') and object == user or is_granted('ROLE_ADMIN')"
        ],
        "delete" => [
            "access_control" => "is_granted('IS_AUTHENTICATED_FULLY') and object == user or is_granted('ROLE_ADMIN')"
        ]
    ]
)]
#[ORM\HasLifecycleCallbacks]
#[UniqueEntity(fields: ["stageName"], message: "This artist already exists")]
class Artist
{

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups (["artist:get", "artist:post"])]
    #[ORM\Column(type: "string", length: 55, nullable: true)]
    #[Assert\NotBlank]
    private $lastname;

    #[Groups (["artist:get", "artist:post"])]
    #[ORM\Column(type: "string", length: 55, nullable: true)]
    #[Assert\NotBlank]
    private $firstname ;

    #[Groups (["artist:get", "artist:post"])]
    #[ORM\Column(type: "string", length: 55, nullable: true)]
    #[Assert\NotBlank]
    private $stageName ;

    #[Groups (["artist:get", "artist:post"])]
    #[ORM\Column(type: "string", length: 10, nullable: true)]
    private $phoneNumber;

    #[ORM\Column(type: "boolean", options: ["default" => "0"])]
    private $validatedAccount = false;

    #[Groups(["artist:get"])]
    #[ORM\Column(type: "datetime")]
    private $createdAt;

    #[Groups(["artist:get"])]
    #[ORM\Column(type: "datetime", nullable: true)]
    private $updatedAt;

    #[Groups(["artist:get"])]
    #[ORM\Column(type: "datetime", nullable: true)]
    private $deletedAt;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'artists')]
    private Collection $events;

    public function __construct()
    {
        $this->events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getStageName(): ?string
    {
        return $this->stageName;
    }

    public function setStageName(?string $stageName): self
    {
        $this->stageName = $stageName;

        return $this;
    }

    public function isValidatedAccount(): ?bool
    {
        return $this->validatedAccount;
    }

    public function setValidatedAccount(bool $validatedAccount): self
    {
        $this->validatedAccount = $validatedAccount;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

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
            $event->addArtist($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): self
    {
        if ($this->events->removeElement($event)) {
            $event->removeArtist($this);
        }

        return $this;
    }
}
