<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class AuthorisedController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected ?User $doctor = null;
}
