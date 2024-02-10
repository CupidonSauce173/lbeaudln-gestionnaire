<?php

namespace Lbeaudln\Gestionnaire\Models;

use DateTime;
use JsonSerializable;

class Mortgage implements JsonSerializable
{
    private int $id;
    private Customer $customer;
    private float $amount;
    private float $rate;
    private string $type;
    private string $terms;
    private DateTime $deadline;
    private string $bank;
    private string $structure;

    public function __construct(int    $id, Customer $customer, float $amount, float $rate, string $type,
                                string $terms, DateTime $deadline, string $bank, string $structure)
    {
        $this->id = $id;
        $this->customer = $customer;
        $this->amount = $amount;
        $this->rate = $rate;
        $this->type = $type;
        $this->terms = $terms;
        $this->deadline = $deadline;
        $this->bank = $bank;
        $this->structure = $structure;
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

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): void
    {
        $this->rate = $rate;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): void
    {
        $this->type = $type;
    }

    public function getTerms(): string
    {
        return $this->terms;
    }

    public function setTerms(string $terms): void
    {
        $this->terms = $terms;
    }

    public function getDeadLine(): DateTime
    {
        return $this->deadline;
    }


    // setters

    public function setDeadLine(DateTime $deadline): void
    {
        $this->deadline = $deadline;
    }

    public function getBank(): string
    {
        return $this->bank;
    }

    public function setBank(string $bank): void
    {
        $this->bank = $bank;
    }

    public function getStructure(): string
    {
        return $this->structure;
    }

    public function setStructure(string $structure): void
    {
        $this->structure = $structure;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'customer' => $this->customer->getId(),
            'amount' => $this->amount,
            'rate' => $this->rate,
            'type' => $this->type,
            'terms' => $this->terms,
            'deadline' => $this->deadline->format('Y-m-d'),
            'bank' => $this->bank,
        ];
    }
}
