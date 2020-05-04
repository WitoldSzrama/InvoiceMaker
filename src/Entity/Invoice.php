<?php

namespace App\Entity;

use Doctrine\DBAL\Types\DateType;
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
     * @ORM\OneToOne(targetEntity="App\Entity\Company", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $forCompany;

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

    public function getForCompany(): ?Company
    {
        return $this->forCompany;
    }

    public function setForCompany(Company $forCompany): self
    {
        $this->forCompany = $forCompany;

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

    public function getPayTo(): ?int
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
}
