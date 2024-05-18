<?php

namespace App\Providers;

use App\Repository\AppointmentRepository;
use App\Repository\PatientRepository;
use App\Repository\UserRepository;

class RepositoryProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserRepository::class, function ($app) {
            return new UserRepository($app->make('auth')->user());
        });

        $this->app->bind(PatientRepository::class, function ($app) {
            return new PatientRepository($app->make('auth')->user());
        });

        $this->app->bind(AppointmentRepository::class, function ($app) {
            return new AppointmentRepository($app->make('auth')->user());
        });
    }
}
