<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Validator as CustomAssert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields={"email"}, message="message.unique")
 */
class User extends AbstractCompany implements UserInterface
{
    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $termsAccepted = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Company", mappedBy="user", cascade={"persist", "remove"})
     */
    private $companies;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Invoice", mappedBy="user", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $invoices;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Product", mappedBy="user")
     */
    private $products;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $baseNumber = 1;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $baseCurrency = 'PLN';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $invoiceNumberTemplate = '$Y/$N';

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @CustomAssert\BaseVat(message="vatError")
     */
    private $baseVat = '0, 3, 5, 7, 8, 23';

    public function __construct()
    {
        $this->companies = new ArrayCollection();
        $this->invoices = new ArrayCollection();
        $this->products = new ArrayCollection();
        $this->contactEmail = $this->email;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getTermsAccepted(): ?bool
    {
        return $this->termsAccepted;
    }

    public function setTermsAccepted(bool $termsAccepted): self
    {
        $this->termsAccepted = $termsAccepted;

        return $this;
    }

    /**
     * @return Collection|Company[]
     */
    public function getCompanies(): Collection
    {
        return $this->companies;
    }

    public function addCompany(Company $company): self
    {
        if (!$this->companies->contains($company)) {
            $this->companies[] = $company;
            $company->setUser($this);
        }

        return $this;
    }

    public function removeCompany(Company $company): self
    {
        if ($this->companies->contains($company)) {
            $this->companies->removeElement($company);
            // set the owning side to null (unless already changed)
            if ($company->getUser() === $this) {
                $company->setUser(null);
            }
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
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): self
    {
        if ($this->invoices->contains($invoice)) {
            $this->invoices->removeElement($invoice);
            // set the owning side to null (unless already changed)
            if ($invoice->getByCompany() === $this) {
                $invoice->setByCompany(null);
            }
        }

        return $this;
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
            $product->setUser($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getUser() === $this) {
                $product->setUser(null);
            }
        }

        return $this;
    }

    public function getBaseNumber(): ?int
    {
        return $this->baseNumber;
    }

    public function setBaseNumber(?int $baseNumber): self
    {
        $this->baseNumber = $baseNumber;

        return $this;
    }

    public function getBaseCurrency(): ?string
    {
        return $this->baseCurrency;
    }

    public function setBaseCurrency(?string $baseCurrency): self
    {
        $this->baseCurrency = $baseCurrency;

        return $this;
    }

    public function getInvoiceNumberTemplate(): ?string
    {
        return $this->invoiceNumberTemplate;
    }

    public function setInvoiceNumberTemplate(?string $invoiceNumberTemplate): self
    {
        $this->invoiceNumberTemplate = $invoiceNumberTemplate;

        return $this;
    }

    public function getBaseVat(): ?string
    {
        return $this->baseVat;
    }

    public function setBaseVat(?string $baseVat): self
    {
        $this->baseVat = $baseVat;

        return $this;
    }
}
