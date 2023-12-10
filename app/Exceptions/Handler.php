<?php

namespace App\Exceptions;

use App\Domain\Appointment\AppointmentException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (AppointmentException $e) {
            if (php_sapi_name() !== 'cli') {
                $e->status = 400;
            }

            throw $e;
        });

        $this->reportable(function (Throwable $e) {
            //
        });
    }
}
