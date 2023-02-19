<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['event:read']
            ]
        ],
        'post' => [
            'denormalization_context' => [
                'groups' => ['event:write']
            ]
        ]
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['event:read']
            ]
        ],
        'put' => [
            'denormalization_context' => [
                'groups' => ['event:write']
            ]
        ]
    ]
)]
class Event
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 3, max: 255)]
    #[Groups(['event:read', 'event:write'])]
    private ?string $name = null;

    #[Assert\NotBlank]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\ManyToOne(inversedBy: 'events')]
    #[Groups(['event:read', 'event:write'])]
    private ?Room $room = null;

    #[Assert\NotBlank]
    #[Assert\Count(min: 1)]
    #[ORM\JoinTable(name: 'event_artist')]
    #[Groups(['event:read', 'event:write'])]
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'events')]
    private Collection $artists;

    #[Assert\NotBlank]
    #[Assert\Positive]
    #[ORM\Column(type: "float")]
    #[Groups(['event:read', 'event:write'])]
    private ?int $price = null;

    #[Groups(['event:read', 'event:write'])]
    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Schedule::class)]
    private Collection $schedules;

    public function __construct()
    {
        $this->artists = new ArrayCollection();
        $this->schedules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    /**
     * @return Collection<int, Artist>
     */
    public function getArtists(): Collection
    {
        return $this->artists;
    }

    public function addArtist(User $artist): self
    {
        if (!$this->artists->contains($artist)) {
            $this->artists->add($artist);
        }

        return $this;
    }

    public function removeArtist(User $artist): self
    {
        $this->artists->removeElement($artist);

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Schedule>
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(Schedule $schedule): self
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules->add($schedule);
            $schedule->setEvent($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): self
    {
        if ($this->schedules->removeElement($schedule)) {
            // set the owning side to null (unless already changed)
            if ($schedule->getEvent() === $this) {
                $schedule->setEvent(null);
            }
        }

        return $this;
    }
}
