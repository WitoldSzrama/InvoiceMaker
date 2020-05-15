<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company extends AbstractCompany
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="companies")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invoice", mappedBy="forCompany", cascade={"remove"})
     */
    private $invoices;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invoice", mappedBy="byCompany", cascade={"remove"})
     */
    private $userInvoices;

    public function __construct()
    {
        $this->invoices = new ArrayCollection();
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
    public function getUserInvoices(): Collection
    {
        return $this->userInvoices;
    }

    public function addUserInvoice(Invoice $invoice): self
    {
        if (!$this->userInvoices->contains($invoice)) {
            $this->userInvoices[] = $invoice;
            $invoice->setByCompany($this);
        }

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
            $invoice->setForCompany($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoices->contains($invoice)) {
            $this->invoices->removeElement($invoice);
            // set the owning side to null (unless already changed)
            if ($invoice->getForCompany() === $this) {
                $invoice->setForCompany(null);
            }
        }

        return $this;
    }


}