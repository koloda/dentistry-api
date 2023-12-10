<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repository\UserRepository;
use App\Services\LoginServeice;
use App\Services\SmsService;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private SmsService $smsService,
        private LoginServeice $loginServeice,
        private UserRepository $userRepository,
    ) {
    }

    /**
     * Handle the incoming request.
     */
    public function sendSms()
    {
        $this->validate(request(), [
            'phone' => 'required|string',
        ]);

        $phone = request('phone');
        $user = $this->userRepository->getUserByPhone($phone);
        $randomCode = $this->loginServeice->startLoginUsingSms($user);
        $this->smsService->send($phone, 'Your verification code is: '.$randomCode);

        return [
            'message' => 'SMS sent successfully',
            'code' => config('app.env') === 'local' ? $randomCode : null,
        ];
    }

    /**
     * Handle the incoming request.
     */
    public function verifySms()
    {
        $this->validate(request(), [
            'phone' => 'required|string',
            'code' => 'required|string',
        ]);

        $phone = request('phone');
        $code = request('code');
        $user = $this->userRepository->getUserByPhone($phone);
        $this->loginServeice->verifySms($user, $code);

        // return sanctum auth token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'message' => 'SMS verified successfully',
            'token' => $token,
        ];
    }
}
