<?php

namespace Lbeaudln\Gestionnaire\Services;

use Exception;
use Twilio\Exceptions\ConfigurationException;
use Twilio\Rest\Client;

class SmsService
{
    private Client $client;
    private string $verifySid;

    private string $SID = "";
    private string $auth = "";

    /**
     * @throws ConfigurationException
     */
    public function __construct()
    {
        $this->verifySid = '';
        $this->client = new Client($this->SID, $this->auth);
    }

    public function sendVerificationCode($phoneNumber): false|string|null
    {
        try {
            $verification = $this->client->verify->v2->services($this->verifySid)
                ->verifications
                ->create($phoneNumber, "sms");

            return $verification->status;
        } catch (Exception $e) {
            return false;
        }
    }

    public function verifyCode($phoneNumber, $code): bool
    {
        try {
            $verificationCheck = $this->client->verify->v2->services($this->verifySid)
                ->verificationChecks
                ->create($code, array('to' => $phoneNumber));

            return $verificationCheck->status === 'approved';
        } catch (Exception $e) {
            return false;
        }
    }
}