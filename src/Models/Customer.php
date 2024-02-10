<?php

namespace Lbeaudln\Gestionnaire\Models;

use DateTime;

class Customer
{
    private int $id;
    private string $firstName;
    private string $lastName;
    private string $homePhone;
    private string $cellPhone;
    private string $email;
    private string $homeAddress;
    private DateTime $registerDate;

    public function __construct(int $id, string $firstName, string $lastName, string $homePhone, string $cellPhone, string $email, string $homeAddress, DateTime $registerDate)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->homePhone = $homePhone;
        $this->cellPhone = $cellPhone;
        $this->email = $email;
        $this->homeAddress = $homeAddress;
        $this->registerDate = $registerDate;
    }

    // getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getHomePhone(): string
    {
        return $this->homePhone;
    }

    public function setHomePhone(string $homePhone): void
    {
        $this->homePhone = $homePhone;
    }

    public function getCellPhone(): string
    {
        return $this->cellPhone;
    }

    // setters

    public function setCellPhone(string $cellPhone): void
    {
        $this->cellPhone = $cellPhone;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getHomeAddress(): string
    {
        return $this->homeAddress;
    }

    public function setHomeAddress(string $homeAddress): void
    {
        $this->homeAddress = $homeAddress;
    }

    public function getRegisterDate(): DateTime
    {
        return $this->registerDate;
    }
}