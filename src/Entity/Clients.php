<?php

namespace App\Entity;

use App\Repository\ClientsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ClientsRepository::class)]
class Clients
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["invoice"])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["invoice"])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(["invoice"])]
    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(["invoice"])]
    private ?string $phone = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["invoice"])]
    private ?string $address = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $creation_date = null;

    #[ORM\OneToMany(mappedBy: 'clients', targetEntity: Accounts::class)]
    private Collection $accounts;

    #[ORM\OneToMany(mappedBy: 'clients', targetEntity: Invoices::class)]
    private Collection $invoices;

    #[ORM\OneToMany(mappedBy: 'clients', targetEntity: Credits::class)]
    private Collection $credits;

    public function __construct()
    {
        $this->accounts = new ArrayCollection();
        $this->invoices = new ArrayCollection();
        $this->credits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeImmutable
    {
        return $this->creation_date;
    }

    public function setCreationDate(\DateTimeImmutable $creation_date): self
    {
        $this->creation_date = $creation_date;

        return $this;
    }

    /**
     * @return Collection<int, Accounts>
     */
    public function getAccounts(): Collection
    {
        return $this->accounts;
    }

    public function addAccount(Accounts $account): self
    {
        if (!$this->accounts->contains($account)) {
            $this->accounts->add($account);
            $account->setClients($this);
        }

        return $this;
    }

    public function removeAccount(Accounts $account): self
    {
        if ($this->accounts->removeElement($account)) {
            // set the owning side to null (unless already changed)
            if ($account->getClients() === $this) {
                $account->setClients(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invoices>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoices $invoice): self
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setClients($this);
        }

        return $this;
    }

    public function removeInvoice(Invoices $invoice): self
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getClients() === $this) {
                $invoice->setClients(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Credits>
     */
    public function getCredits(): Collection
    {
        return $this->credits;
    }

    public function addCredit(Credits $credit): self
    {
        if (!$this->credits->contains($credit)) {
            $this->credits->add($credit);
            $credit->setClients($this);
        }

        return $this;
    }

    public function removeCredit(Credits $credit): self
    {
        if ($this->credits->removeElement($credit)) {
            // set the owning side to null (unless already changed)
            if ($credit->getClients() === $this) {
                $credit->setClients(null);
            }
        }

        return $this;
    }
}
