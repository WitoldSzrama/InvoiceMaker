<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $netValue;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $grossValue;

    /**
     * @ORM\Column(type="integer")
     */
    private $vat;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $currency;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $forPeriod;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="products")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Invoice", mappedBy="products")
     */
    private $invoices;

    public function __construct()
    {
        $this->invoices = new ArrayCollection();
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

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getNetValue(): ?int
    {
        return $this->netValue;
    }

    public function setNetValue(int $netValue): self
    {
        $this->netValue = $netValue;

        return $this;
    }

    public function getGrossValue(): ?int
    {
        return $this->grossValue;
    }

    public function setGrossValue(int $grossValue): self
    {
        $this->grossValue = $grossValue;

        return $this;
    }

    public function getVat(): ?int
    {
        return $this->vat;
    }

    public function setVat(int $vat): self
    {
        $this->vat = $vat;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getForPeriod(): ?string
    {
        return $this->forPeriod;
    }

    public function setForPeriod(?string $forPeriod): self
    {
        $this->forPeriod = $forPeriod;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Invoice[]
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): self
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices[] = $invoice;
            $invoice->addProduct($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoices->contains($invoice)) {
            $this->invoices->removeElement($invoice);
            $invoice->removeProduct($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name . ' NET:' . $this->netValue .'  VAT:' .$this->vat . ' QUANTITY:' . $this->quantity;
    }
}
