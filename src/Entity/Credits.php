<?php

namespace App\Entity;

use App\Repository\CreditsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CreditsRepository::class)]
class Credits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $credit_amount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $credit_date = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $reason = null;

    #[ORM\ManyToOne(inversedBy: 'credits')]
    private ?Clients $clients = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreditAmount(): ?string
    {
        return $this->credit_amount;
    }

    public function setCreditAmount(string $credit_amount): self
    {
        $this->credit_amount = $credit_amount;

        return $this;
    }

    public function getCreditDate(): ?\DateTimeInterface
    {
        return $this->credit_date;
    }

    public function setCreditDate(\DateTimeInterface $credit_date): self
    {
        $this->credit_date = $credit_date;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(?string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getClients(): ?Clients
    {
        return $this->clients;
    }

    public function setClients(?Clients $clients): self
    {
        $this->clients = $clients;

        return $this;
    }
}
