<?php

namespace App\Services;

use App\Models\User;

class LoginServeice
{
    public function sendLoginSms(User $user, SmsService $smsService): string
    {
        $randomCode = $this->randomCode();
        $user->tmp_sms_code = $randomCode;
        $user->save();

        $smsService->send($user->phone, 'Your verification code is: '.$randomCode);

        return $randomCode;
    }

    public function verifySms(User $user, string $code): void
    {
        if ($user->tmp_sms_code !== $code) {
            abort(403, 'Invalid code');
        }

        $user->tmp_sms_code = null;
        $user->save();
    }

    private function randomCode(): int
    {
        return random_int(1000, 9999);
    }
}
