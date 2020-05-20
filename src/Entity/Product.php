<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @Groups("apiProduct")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Groups("apiProduct")
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @Assert\Length(
     *  min = 1,
     *  minMessage = "Your first name must be at least {{ limit }} characters long",
     *  allowEmptyString = false
     * )
     * @Groups("apiProduct")
     * @ORM\Column(type="integer")
     */
    private $quantity;

    /**
     * @Groups("apiProduct")
     * @ORM\Column(type="decimal", nullable=true, scale=2)
     */
    private $netValue;

    /**
     * @Groups("apiProduct")
     * @ORM\Column(type="decimal", nullable=true, precision=10 , scale=2)
     */
    private $grossValue;

    /**
     * @Groups("apiProduct")
     * @ORM\Column(type="integer")
     */
    private $vat;

    /**
     * @Groups("apiProduct")
     * @ORM\Column(type="string", length=20)
     */
    private $currency;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="products")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Invoice", mappedBy="products", cascade={"persist", "remove"})
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

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
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

    public function getNetValue(): ?string
    {
        return $this->netValue;
    }

    public function setNetValue(?string $netValue): self
    {
        $this->netValue = $netValue;

        return $this;
    }

    public function getGrossValue(): ?string
    {
        return $this->grossValue;
    }

    public function setGrossValue(?string $grossValue): self
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

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
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
        return $this->name.' | NET: '.$this->netValue.' | VAT: '.$this->vat.'% | '.$this->quantity;
    }
}
