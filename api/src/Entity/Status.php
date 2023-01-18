<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\StatusRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatusRepository::class)]
#[ApiResource]
class Status
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 9)]
    private ?string $worded = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWorded(): ?string
    {
        return $this->worded;
    }

    public function setWorded(string $worded): self
    {
        $this->worded = $worded;

        return $this;
    }
}
