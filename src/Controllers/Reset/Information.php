<?php

namespace Lbeaudln\Gestionnaire\Controllers\Reset;

use Lbeaudln\Gestionnaire\Services\SmsService;

class Information
{
    public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = $_POST['email'] ?? '';
            $phone = $_POST['phone'] ?? '';

            $smsService = new SmsService();
            $sendStatus = $smsService->sendVerificationCode($phone);
            include __DIR__ . '/../../../views/Reset/code.php';

        } else if ($_SERVER['REQUEST_METHOD'] == 'GET') {
            include __DIR__ . '/../../../views/Reset/information.php';
        }

    }
}
