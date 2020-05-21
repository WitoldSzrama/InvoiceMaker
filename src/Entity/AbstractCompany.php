<?php

namespace App\Entity;

use App\Validator as CustomAssert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\MappedSuperclass()
 */
abstract class AbstractCompany
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     * @Assert\Email()
     */
    private $contactEmail;

    /**
     * @ORM\Column(type="string", length=40)
     * @Assert\Length(
     *     min="2",
     *     minMessage="message.minNameMessage",
     *     max="40"
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="integer", length=10, nullable=true)
     * @Assert\Length(
     *     min="10",
     *     max="10"
     * )
     */
    private $nip;

    /**
     * @ORM\Column(type="integer", length=9, nullable=true)
     * @Assert\Length(
     *     min="7",
     *     max="14"
     * )
     */
    private $regon;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     * @Assert\Length(
     *     max="40"
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=9, nullable=true)
     * @Assert\Length(
     *     max="9"
     * )
     */
    private $postCode;

    /**
     * @ORM\Column(type="string", length=40, nullable=true)
     * @Assert\Length(
     *     max="40"
     * )
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\Length(
     *     max="20"
     * )
     */
    private $stNumber;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     * @CustomAssert\AccountNumber()
     * @Assert\Length(
     *     min="32",
     *     max="32"
     * )
     */
    private $accountNumber;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $localNumber;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $houseNumber;

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(string $email): self
    {
        $this->contactEmail = $email;

        return $this;
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

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getPostCode(): ?string
    {
        return $this->postCode;
    }

    public function setPostCode(?string $postCode): self
    {
        $this->postCode = $postCode;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getStNumber(): ?string
    {
        return $this->stNumber;
    }

    public function setStNumber(?string $stNumber): self
    {
        $this->stNumber = $stNumber;

        return $this;
    }

    public function getAccountNumber(): ?string
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(?string $accountNumber): self
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    public function __toString()
    {
        return $this->getName().' | NIP: '.$this->getNip().' | REGON: '.$this->getRegon();
    }

    public function getLocalNumber(): ?string
    {
        return $this->localNumber;
    }

    public function setLocalNumber(?string $localNumber): self
    {
        $this->localNumber = $localNumber;

        return $this;
    }

    public function getHouseNumber(): ?string
    {
        return $this->houseNumber;
    }

    public function setHouseNumber(?string $houseNumber): self
    {
        $this->houseNumber = $houseNumber;

        return $this;
    }
}
