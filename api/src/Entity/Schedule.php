<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ScheduleRepository;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['schedule:read']],
    denormalizationContext: ['groups' => ['schedule:write']],
//     operations: [
//         'get' => [
//             'normalization_context' => [
//                 'groups' => ['schedule:read']
//             ]
//         ],
//         'post' => [
//             'denormalization_context' => [
//                 'groups' => ['schedule:write']
//             ]
//         ]
//     ],
//     itemOperations: [
//         'get' => [
//             'normalization_context' => [
//                 'groups' => ['schedule:read']
//             ]
//         ],
//         'put' => [
//             'denormalization_context' => [
//                 'groups' => ['schedule:write']
//             ]
//         ]
//     ]
)]
class Schedule
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'schedules')]
    #[Groups(['schedule:read', 'schedule:write'])]
    private ?Event $event = null;

    #[ORM\Column]
    #[Groups(['schedule:read', 'schedule:write', 'event:read'])]
    private ?string $date = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::JSON)]
    #[Groups(['schedule:read', 'schedule:write', 'event:read'])]
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

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
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
