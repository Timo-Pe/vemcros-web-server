<?php

namespace App\Entity;

use App\Repository\UnpaidInvoicesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UnpaidInvoicesRepository::class)]
class UnpaidInvoices
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $due_date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $unpaid_amount = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $alert_date = null;

    #[ORM\Column]
    private ?bool $alert_sent = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $interest_amount = null;

    #[ORM\ManyToOne(inversedBy: 'unpaid_invoice')]
    private ?Invoices $invoices = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->due_date;
    }

    public function setDueDate(\DateTimeInterface $due_date): self
    {
        $this->due_date = $due_date;

        return $this;
    }

    public function getUnpaidAmount(): ?string
    {
        return $this->unpaid_amount;
    }

    public function setUnpaidAmount(string $unpaid_amount): self
    {
        $this->unpaid_amount = $unpaid_amount;

        return $this;
    }

    public function getAlertDate(): ?\DateTimeInterface
    {
        return $this->alert_date;
    }

    public function setAlertDate(?\DateTimeInterface $alert_date): self
    {
        $this->alert_date = $alert_date;

        return $this;
    }

    public function isAlertSent(): ?bool
    {
        return $this->alert_sent;
    }

    public function setAlertSent(bool $alert_sent): self
    {
        $this->alert_sent = $alert_sent;

        return $this;
    }

    public function getInterestAmount(): ?string
    {
        return $this->interest_amount;
    }

    public function setInterestAmount(string $interest_amount): self
    {
        $this->interest_amount = $interest_amount;

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
