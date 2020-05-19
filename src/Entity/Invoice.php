<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\InvoiceRepository")
 */
class Invoice
{
    const WRONG_FILE_CHAR = ['.', '/' ,' ', '\\', ',', ':', ';', "'", '"', '<', '>', '=', '@', '`', '{', '}', '*', '|'];
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="userInvoices", cascade={"persist"})
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

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $salesDate;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="invoices")
     */
    private $user;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->salesDate = new DateTime();
        $payTo = new DateTime();
        $payTo->modify('+14 days');
        $this->payTo = $payTo;
    }

    public function getTotal()
    {
        $total = 0;
        foreach ($this->products as $product) {
            $total += $product->getGrossValue() * $product->getQuantity();
        }

        return $total;
    }

    public function getNetTotal()
    {
        $total = 0;
        foreach ($this->products as $product) {
            $total += $product->getNetValue() * $product->getQuantity();
        }

        return $total;
    }

    public function getInvoiceNumberSlug()
    {
        return str_replace(self::WRONG_FILE_CHAR, '-', $this->getInvoiceNumber());
    }
    
    public function getCurrency()
    {
        return $this->products[0]->getCurrency();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getByCompany(): ?Company
    {
        return $this->byCompany;
    }

    public function setByCompany(?Company $byCompany): self
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

    public function getSalesDate(): ?\DateTimeInterface
    {
        return $this->salesDate;
    }

    public function setSalesDate(?\DateTimeInterface $salesDate): self
    {
        $this->salesDate = $salesDate;

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

    public function __clone()
    {
        if($this->id) {
            $this->id = null;
            $this->setCreatedAt(new DateTime());
            $this->setSalesDate(new DateTime());
            $payTo = new DateTime();
            $payTo->modify('+14 days');
            $this->payTo = $payTo;
        }
    }

}
