<?php

namespace App\Entity;

use App\Repository\AlertsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: AlertsRepository::class)]
class Alerts
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["invoice"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["invoice"])]
    private ?\DateTimeInterface $alert_date = null;

    #[ORM\Column(length: 50)]
    #[Groups(["invoice"])]
    private ?string $alert_type = null;

    #[ORM\Column(length: 20)]
    #[Groups(["invoice"])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'alerts')]
    private ?Invoices $invoices = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAlertDate(): ?\DateTimeInterface
    {
        return $this->alert_date;
    }

    public function setAlertDate(\DateTimeInterface $alert_date): self
    {
        $this->alert_date = $alert_date;

        return $this;
    }

    public function getAlertType(): ?string
    {
        return $this->alert_type;
    }

    public function setAlertType(string $alert_type): self
    {
        $this->alert_type = $alert_type;

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

    public function getInvoices(): ?Invoices
    {
        return $this->invoices;
    }

    public function setInvoices(?Invoices $invoices): self
    {
        $this->invoices = $invoices;

        return $this;
    }
}
