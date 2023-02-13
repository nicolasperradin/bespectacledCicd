<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
#[ApiResource(
    collectionOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['schedule:read']
            ]
        ],
        'post' => [
            'denormalization_context' => [
                'groups' => ['schedule:write']
            ]
        ]
    ],
    itemOperations: [
        'get' => [
            'normalization_context' => [
                'groups' => ['schedule:read']
            ]
        ],
        'put' => [
            'denormalization_context' => [
                'groups' => ['schedule:write']
            ]
        ]
    ]
)]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'schedules')]
    #[Groups(['schedule:read', 'schedule:write'])]
    private ?Event $event = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Groups(['schedule:read', 'schedule:write'])]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::ARRAY)]
    #[Groups(['schedule:read', 'schedule:write'])]
    #[Assert\NotBlank]
    private array $times = [];

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getTimes(): array
    {
        return $this->times;
    }

    public function setTimes(array $times): self
    {
        $this->times = $times;

        return $this;
    }
}
