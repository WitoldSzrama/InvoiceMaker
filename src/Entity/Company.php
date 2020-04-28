<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(
     *     min="2",
     *     minMessage="message.minNameMessage",
     *     max="100"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $nip;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $regon;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $postCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $street;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $stNumber;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $accountNumber;

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

    public function getNip(): ?int
    {
        return $this->nip;
    }

    public function setNip(?int $nip): self
    {
        $this->nip = $nip;

        return $this;
    }

    public function getRegon(): ?int
    {
        return $this->regon;
    }

    public function setRegon(?int $regon): self
    {
        $this->regon = $regon;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function setPostCode(string $postCode): self
    {
        $this->postCode = $postCode;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getStNumber(): ?int
    {
        return $this->stNumber;
    }

    public function setStNumber(int $stNumber): self
    {
        $this->stNumber = $stNumber;

        return $this;
    }

    public function getAccountNumber(): ?int
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(?int $accountNumber): self
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }
}
