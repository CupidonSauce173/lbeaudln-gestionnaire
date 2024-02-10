<?php

namespace Lbeaudln\Gestionnaire\Controllers\Reset;

use Lbeaudln\Gestionnaire\Services\SmsService;

class Code
{
    public function execute(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $code = $_POST['code'] ?? '';
            $phone = $_POST['phone'] ?? '';

            $smsService = new SmsService();
            if ($smsService->verifyCode($phone, $code)) {
                include __DIR__ . '/../../../views/Reset/create.php';
            } else {
                echo "wrong";
            }
        }
    }
}