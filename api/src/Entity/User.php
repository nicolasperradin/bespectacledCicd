<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Controller\UserController;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: "`user`")]
#[ApiResource(
    normalizationContext: ["groups" => ["user:get"]],
    denormalizationContext: ["groups" => ["user:post"]],
    collectionOperations: [
        "get" => [
            "pagination_enabled" => false,
            "access_control" => "is_granted('ROLE_ADMIN')",
            "normalization_context" => ["groups" => ["user:get"]]
        ],
        "post" => [
            "pagination_enabled" => false,
            "denormalization_context" => ["groups" => ["user:post"]]
        ],
        "me" => [
            "method" => "GET",
            "path" => "/users/me",
            "controller" => UserController::class,
            "pagination_enabled" => false
        ]
    ],
    validationGroups: ["user:get", "user:post", "user:put"],
    itemOperations: [
        "get" => [
            "normalization_context" => [
                "groups" => ["user:get"],
                "access_control" => "is_granted('IS_AUTHENTICATED_FULLY') and object == user  or is_granted('ROLE_ADMIN')"
            ]
        ],
        "put" => [
            "denormalization_context" => ["groups" => ["user:put"]],
            "normalization_context" => ["groups" => ["user:get"]],
            "access_control" => "is_granted('IS_AUTHENTICATED_FULLY') and object == user or is_granted('ROLE_ADMIN')"
        ],
        "delete" => [
            "access_control" => "is_granted('IS_AUTHENTICATED_FULLY') and object == user or is_granted('ROLE_ADMIN')"
        ],
        "put-password" => [
            "method" => "PUT",
            "path" => "/users/{id}/reset-password",
            "controller" => ResetPasswordAction::class,
            "denormalization_context" => ["groups" => ["user:reset:password"]],
            "access_control" => "is_granted('IS_AUTHENTICATED_FULLY') and object == user"
        ]
    ]
)]

#[ORM\HasLifecycleCallbacks]
#[UniqueEntity("email")]
class User implements UserInterface, PasswordAuthenticatedUserInterface
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
    #[ORM\Column(type: "integer")]
    #[Groups("user:get")]
    private $id;


    #[Groups(["user:post", "user:get"])]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[ORM\Column(type: "string", length: 180, unique: true)]
    private $email;

    #[ORM\Column(type: "json")]
    #[Groups("user:get")]
    private $roles = [];

    #[ORM\Column(type: "string")]
    #[Assert\NotBlank()]
    #[Assert\Regex(
        pattern: "/(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9]).{7,}/",
        message: "Password must be seven characters long and contain at least one digit, one upper case letter and one lower case letter"
    )]
    private $password;

    #[SerializedName("password")]
    #[Assert\NotBlank]
    #[Groups("user:post")]
    private $plainPassword;


    #[ORM\Column(type: "string", length: 255)]
    #[Groups(["user:post", "user:get"])]
    #[Assert\NotBlank]
    private $username;

    #[ORM\Column(type: "boolean", options: ["default" => "0"])]
    private $enabled = false;

    #[ORM\Column(type: "string", length: 40, nullable: true)]
    private $confirmationToken;

    #[Groups(["user:get"])]
    #[ORM\Column(type: "datetime")]
    private $createdAt;

    #[Groups(["user:get"])]
    #[ORM\Column(type: "datetime", nullable: true)]
    private $updatedAt;


    #[ORM\Column(name: "password_change_date", type: "integer", nullable: true)]
    private $passwordChangeDate;

    #[Groups(["user:reset:password"])]
    #[SecurityAssert\UserPassword(message: "Wrong value for your current password")]
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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
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
}
