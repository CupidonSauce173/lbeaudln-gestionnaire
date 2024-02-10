<?php

namespace Lbeaudln\Gestionnaire\Models;

use DateInterval;
use DateTime;

class SMS
{
    private int $id;
    private int $userId;
    private string $code;
    private DateTime $registerDate;
    private DateTime $deleteDate;

    // Twillio creds.
    private string $SID = "";
    private string $auth = "";
    private string $verify_sid = "";

    public function __construct(int $id, int $userId, string $code, DateTime $registerDate)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->code = $code;

        $registerDate->add(new DateInterval('PT10M'));
        $this->registerDate = $registerDate;
    }

    // getters

    public static function sendResetCode(string $email, string $phoneNumer): bool
    {
        return true;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    // setters

    public function getRegisterDate(): DateTime
    {
        return $this->registerDate;
    }

    public function getDeleteDate(): DateTime
    {
        return $this->deleteDate;
    }

    // utils

    public function setDeleteDate(DateTime $deleteDate): void
    {
        $this->deleteDate = $deleteDate;
    }
}