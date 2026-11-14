<?php

namespace App\Modules\SmartEdu\Auth;

use App\Http\Controllers\Controller;
use App\Modules\SmartEdu\Auth\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $service) {}

    public function landing()
    {
        return $this->service->landing();
    }

    public function loginForm()
    {
        return $this->service->loginForm();
    }

    public function login(Request $request)
    {
        return $this->service->login($request);
    }

    public function logout()
    {
        return $this->service->logout();
    }
}
