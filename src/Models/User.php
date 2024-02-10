<?php

namespace Lbeaudln\Gestionnaire\Models;

use DateTime;

class User
{
    private int $id;
    private string $username;
    private string $email;
    private string $firstName;
    private string $lastName;
    private string $role;
    private string $phoneNumber;
    private DateTime $registerDate;
    private string $hashedPassword;

    public function __construct(int $id, string $username, string $email, string $firstName, string $lastName, string $role, string $phoneNumber, DateTime $registerDate, string $hashedPassword)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->role = $role;
        $this->phoneNumber = $phoneNumber;
        $this->registerDate = $registerDate;
        $this->hashedPassword = $hashedPassword;
    }

    // getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
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

    // setters

    public function getRole(): string
    {
        return $this->role;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getRegisterDate(): DateTime
    {
        return $this->registerDate;
    }

    public function getPassword(): string
    {
        return $this->hashedPassword;
    }

    public function setPassword(string $hashedPassword): void
    {
        $this->hashedPassword = $hashedPassword;
    }

    // utils
    public function checkPasswords(string $password): bool
    {
        return $password == $this->hashedPassword;
    }
}