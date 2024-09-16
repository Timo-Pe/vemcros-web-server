<?php

namespace App\Entity;

use App\Repository\InvoicesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: InvoicesRepository::class)]
class Invoices
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["invoice"])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(["invoice"])]
    private ?string $total_amount = null;

    #[ORM\Column]
    #[Groups(["invoice"])]
    private ?\DateTimeImmutable $invoice_date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(["invoice"])]
    private ?\DateTimeInterface $due_date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    #[Groups(["invoice"])]
    private ?string $interest_rate = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(["invoice"])]
    private ?string $interest_amount = null;

    #[ORM\Column(length: 20)]
    #[Groups(["invoice"])]
    private ?string $status = null;

    #[ORM\ManyToOne(inversedBy: 'invoices')]
    #[Groups(["invoice"])]
    private ?Clients $clients = null;

    #[ORM\OneToMany(mappedBy: 'invoices', targetEntity: Payments::class)]
    #[Groups(["invoice"])]
    private Collection $payments;

    #[ORM\OneToMany(mappedBy: 'invoices', targetEntity: UnpaidInvoices::class)]
    private Collection $unpaid_invoice;

    #[ORM\OneToMany(mappedBy: 'invoices', targetEntity: Alerts::class)]
    #[Groups(["invoice"])]
    private Collection $alerts;

    public function __construct()
    {
        $this->payments = new ArrayCollection();
        $this->unpaid_invoice = new ArrayCollection();
        $this->alerts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTotalAmount(): ?string
    {
        return $this->total_amount;
    }

    public function setTotalAmount(string $total_amount): self
    {
        $this->total_amount = $total_amount;

        return $this;
    }

    public function getInvoiceDate(): ?\DateTimeImmutable
    {
        return $this->invoice_date;
    }

    public function setInvoiceDate(\DateTimeImmutable $invoice_date): self
    {
        $this->invoice_date = $invoice_date;

        return $this;
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

    public function getInterestRate(): ?string
    {
        return $this->interest_rate;
    }

    public function setInterestRate(string $interest_rate): self
    {
        $this->interest_rate = $interest_rate;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

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

    /**
     * @return Collection<int, Payments>
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payments $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments->add($payment);
            $payment->setInvoices($this);
        }

        return $this;
    }

    public function removePayment(Payments $payment): self
    {
        if ($this->payments->removeElement($payment)) {
            // set the owning side to null (unless already changed)
            if ($payment->getInvoices() === $this) {
                $payment->setInvoices(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UnpaidInvoices>
     */
    public function getUnpaidInvoice(): Collection
    {
        return $this->unpaid_invoice;
    }

    public function addUnpaidInvoice(UnpaidInvoices $unpaidInvoice): self
    {
        if (!$this->unpaid_invoice->contains($unpaidInvoice)) {
            $this->unpaid_invoice->add($unpaidInvoice);
            $unpaidInvoice->setInvoices($this);
        }

        return $this;
    }

    public function removeUnpaidInvoice(UnpaidInvoices $unpaidInvoice): self
    {
        if ($this->unpaid_invoice->removeElement($unpaidInvoice)) {
            // set the owning side to null (unless already changed)
            if ($unpaidInvoice->getInvoices() === $this) {
                $unpaidInvoice->setInvoices(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Alerts>
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

    public function addAlert(Alerts $alert): self
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts->add($alert);
            $alert->setInvoices($this);
        }

        return $this;
    }

    public function removeAlert(Alerts $alert): self
    {
        if ($this->alerts->removeElement($alert)) {
            // set the owning side to null (unless already changed)
            if ($alert->getInvoices() === $this) {
                $alert->setInvoices(null);
            }
        }

        return $this;
    }
}
