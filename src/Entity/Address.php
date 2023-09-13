<?php

namespace App\Entity;

use App\Repository\AddressRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Intl\Countries;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $city = null;

    #[ORM\Column(length: 100)]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    private ?string $street1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $street2 = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $postalCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function getCountryFull(): ?string
    {
        return Countries::getName($this->country);
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getStreet1(): ?string
    {
        return $this->street1;
    }

    public function setStreet1(string $street1): static
    {
        $this->street1 = $street1;

        return $this;
    }

    public function getStreet2(): ?string
    {
        return $this->street2;
    }

    public function setStreet2(?string $street2): static
    {
        $this->street2 = $street2;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(?string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getFullAddress();
    }

    public function getFullAddress(): string
    {
        return $this->getLightAddress().', '.$this->getCountryFull();
    }

    public function getLightAddress(): string
    {
        $str = $this->getStreetAddress();
        if ($this->postalCode) {
            $str.= ', '.$this->postalCode;
        }

        return $str.', '.$this->city;
    }

    public function getStreetAddress(): string
    {
        $str = $this->street1;
        if ($this->street2) {
            $str.= ', '.$this->street2;
        }

        return $str;
    }
}
