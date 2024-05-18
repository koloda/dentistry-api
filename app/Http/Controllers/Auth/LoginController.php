<?php

namespace App\Http\Controllers\Auth;

use App\Repository\UserRepository;
use App\Services\LoginServeice;
use App\Services\SmsService;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use ValidatesRequests;

    /**
     * Create a new controller instance.
     */
    public function __construct(
        private readonly SmsService $smsService,
        private readonly LoginServeice $loginService,
        private readonly UserRepository $userRepository,
    ) {
    }

    /**
     * Handle the incoming request.
     *
     * @throws ValidationException
     */
    // @phpstan-ignore-next-line
    public function sendSms(): array
    {
        $this->validate(request(), [
            'phone' => 'required|string|exists:users,phone',
        ]);

        $phone = request()->string('phone');
        $user = $this->userRepository->getUserByPhone($phone);

        $randomCode = $this->loginService->sendLoginSms($user, $this->smsService);

        return [
            'message' => 'SMS sent successfully',
            'code' => $randomCode,
        ];
    }

    /**
     * Handle the incoming request.
     *
     * @throws ValidationException
     */
    // @phpstan-ignore-next-line
    public function verifySms(): array
    {
        $this->validate(request(), [
            'phone' => 'required|string|exists:users,phone',
            'code' => 'required|string',
        ]);

        $phone = request()->string('phone');
        $code = request()->string('code');
        $user = $this->userRepository->getUserByPhone($phone);
        $this->loginService->verifySms($user, $code);

        // return sanctum auth token
        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'message' => 'SMS verified successfully',
            'token' => $token,
        ];
    }
}
