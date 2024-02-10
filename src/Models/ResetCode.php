<?php

namespace Lbeaudln\Gestionnaire\Models;

class ResetCode
{
    private string $email;
    private string $phone;
    private string $code;

    // Twillio creds.
    private string $SID = "";
    private string $auth = "";
    private string $verify_sid = "";

    public function __construct(string $email, string $phone)
    {
        $this->email = $email;
        $this->phone = $phone;

        // generate code

    }

    public function send(): bool
    {
        return true;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    public function check(string $code): bool
    {
        return $this->code = $code;
    }
}