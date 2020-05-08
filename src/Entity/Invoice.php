<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvoiceRepository")
 */
class Invoice
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="invoices")
     * @ORM\JoinColumn(nullable=false)
     */
    private $byCompany;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $payTo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $invoiceNumber;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="invoices")
     */
    private $forCompany;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="invoices", cascade={"persist"})
     */
    private $products;

    public function __construct()
    {
        $this->products = new ArrayCollection();
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->products as $product) {
            $total += $product->getGrossValue();
        }

        return $total;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getByCompany(): ?User
    {
        return $this->byCompany;
    }

    public function setByCompany(?User $byCompany): self
    {
        $this->byCompany = $byCompany;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getPayTo(): ?\DateTimeInterface
    {
        return $this->payTo;
    }

    public function setPayTo(\DateTimeInterface $payFrom): self
    {
        $this->payTo = $payFrom;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getForCompany(): ?Company
    {
        return $this->forCompany;
    }

    public function setForCompany(?Company $forCompany): self
    {
        $this->forCompany = $forCompany;

        return $this;
    }

    public function overdue()
    {
        if ($this->payTo < new \DateTime()){
            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    /**
     * @param string $invoiceNumber
     */
    public function setInvoiceNumber($invoiceNumber): void
    {
        $this->invoiceNumber = $invoiceNumber;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
        }

        return $this;
    }

}