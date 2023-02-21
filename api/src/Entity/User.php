<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\UserController;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\ResetPasswordAction;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ApiResource(
    normalizationContext: ['groups' => ['user:get']],
    denormalizationContext: ['groups' => ['user:post']],
    validationContext: ['groups' => ['user:get', 'user:post', 'user:put']],
    operations: [
        new GetCollection(
            paginationEnabled: false,
            security: "is_granted('ROLE_ADMIN', user)'",
            normalizationContext: ['groups' => ['user:get']]
        ),
        new Post(
            paginationEnabled: false,
            denormalizationContext: ['groups' => ['user:post']]
        ),
        new Get(
            uriTemplate: '/users/me',
            controller: UserController::class,
            paginationEnabled: false
        ),
        new Get(
            // uriTemplate: '/users/{id}',
            normalizationContext: [
                'groups' => ['user:get'],
                'access_control' => "is_granted('IS_AUTHENTICATED_FULLY') and object == user or is_granted('ROLE_ADMIN')"
            ]
        ),
        new Put(
            // uriTemplate: '/users/{id}',
            denormalizationContext: ['groups' => ['user:put']],
            normalizationContext: ['groups' => ['user:get']],
            security: "is_granted('IS_AUTHENTICATED_FULLY') and object == user or is_granted('ROLE_ADMIN')"
        ),
        new Delete(
            // uriTemplate: '/users/{id}',
            security: "is_granted('IS_AUTHENTICATED_FULLY') and object == user or is_granted('ROLE_ADMIN')"
        ),
        // new Put(
        //     uriTemplate: '/users/{id}/reset-password',
        //     controller: ResetPasswordAction::class,
        //     denormalizationContext: ['groups' => ['user:reset:password']],
        //     security: "is_granted('IS_AUTHENTICATED_FULLY') and object == user"
        // )
    ]
)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity('email', message: 'This email is already used.')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function updateTimestamps(): void
    {
        if ($this->getCreatedAt() === null) $this->setCreatedAt(new \DateTimeImmutable());
        $this->setUpdatedAt(new \DateTimeImmutable());
    }

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups('user:get')]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['user:get', 'user:post'])]
    private ?string $email = null;

    #[ORM\Column()]
    #[Groups('user:get')]
    private array $roles = [];

    #[ORM\Column()]
    #[Assert\Regex(
        pattern: "/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
        message: "Password must be seven characters long and contain at least one digit, one upper case letter and one lower case letter"
    )]
    #[Groups(['user:post', 'user:get'])]
    private ?string $password = null;

    #[SerializedName("password")]
    #[Assert\NotBlank]
    #[Groups("user:post")]
    private $plainPassword;

    #[ORM\Column(type: 'string', length: 255)]
    #[Groups(['user:post', 'user:get'])]
    #[Assert\NotBlank]
    private $username;

    #[ORM\Column(type: 'boolean', options: ['default' => '0'])]
    private $enabled = false;

    #[ORM\Column(length: 40, nullable: true)]
    private $confirmationToken;

    #[Groups(["user:get"])]
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[Groups(["user:get"])]
    #[ORM\Column]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(name: "password_change_date", type: "integer", nullable: true)]
    private $passwordChangeDate;

    // TODO this class below doesn't exist
    // #[SecurityAssert\UserPassword(message: "Wrong value for your current password")]
    #[Groups(["user:reset:password"])]
    private $oldPassword;

    #[Groups(["user:reset:password"])]
    #[Assert\NotBlank(groups: ["user:reset:password"])]
    #[Assert\Regex(
        pattern: "/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
        message: "Password must be seven characters long and contain at least one digit, one upper case letter and one lower case letter"
    )]
    private $newPassword;


    #[Groups(["user:reset:password"])]
    #[Assert\NotBlank(groups: ["user:reset:password"])]
    #[Assert\Expression(
        "this.getNewPassword() === this.getNewRetypedPassword()",
        message: "passwords does not match"
    )]
    private $newRetypedPassword;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Booking::class)]
    #[Groups("user:get")]
    private Collection $bookings;

    #[ORM\OneToMany(mappedBy: 'buyer', targetEntity: Ticket::class)]
    #[Groups("user:get")]
    private Collection $tickets;

    #[ORM\OneToMany(mappedBy: 'buyer', targetEntity: Transaction::class)]
    private Collection $transactions;

    #[ORM\Column(type: "boolean", options: ["default" => "0"])]
    private $validatedAccountArtist = false;

    #[Groups(["user:get"])]
    #[ORM\Column(type: "datetime", nullable: true)]
    private $deletedAt;

    #[ORM\ManyToMany(targetEntity: Event::class, mappedBy: 'artists')]
    private Collection $events;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
        $this->tickets = new ArrayCollection();
        $this->transactions = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    public function isValidatedAccountArtist(): ?bool
    {
        return $this->validatedAccountArtist;
    }

    public function setValidatedAccountArtist(bool $validatedAccountArtist): self
    {
        $this->validatedAccountArtist = $validatedAccountArtist;

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


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $password): self
    {
        $this->plainPassword = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }


    public function getPasswordChangeDate()
    {
        return $this->passwordChangeDate;
    }

    public function setPasswordChangeDate($passwordChangeDate)
    {
        $this->passwordChangeDate = $passwordChangeDate;

        return $this;
    }

    public function getNewPassword()
    {
        return $this->newPassword;
    }

    public function setNewPassword($newPassword)
    {
        $this->newPassword = $newPassword;

        return $this;
    }

    public function getNewRetypedPassword()
    {
        return $this->newRetypedPassword;
    }

    public function setNewRetypedPassword($newRetypedPassword)
    {
        $this->newRetypedPassword = $newRetypedPassword;

        return $this;
    }

    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    public function setOldPassword($oldPassword)
    {
        $this->oldPassword = $oldPassword;

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
            $booking->setClient($this);
        }

        return $this;
    }

    public function removeBooking(Booking $booking): self
    {
        if ($this->bookings->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getClient() === $this) {
                $booking->setClient(null);
            }
        }

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
            $ticket->setBuyer($this);
        }

        return $this;
    }

    public function removeTicket(Ticket $ticket): self
    {
        if ($this->tickets->removeElement($ticket)) {
            // set the owning side to null (unless already changed)
            if ($ticket->getBuyer() === $this) {
                $ticket->setBuyer(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setBuyer($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getBuyer() === $this) {
                $transaction->setBuyer(null);
            }
        }

        return $this;
    }
}
