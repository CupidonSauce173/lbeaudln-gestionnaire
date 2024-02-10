<?php

namespace Lbeaudln\Gestionnaire\Models;

use DateTime;

class Comment
{
    private int $id;
    private Customer $customer;
    private User $author;
    private string $body;
    private DateTime $registerDate;

    public function __construct(int $id, Customer $customer, User $user, string $body, DateTime $registerDate)
    {
        $this->id = $id;
        $this->customer = $customer;
        $this->author = $user;
        $this->body = $body;
        $this->registerDate = $registerDate;
    }

    // getters
    public function getId(): int
    {
        return $this->id;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    // setters

    public function getRegisterDate(): DateTime
    {
        return $this->registerDate;
    }
}