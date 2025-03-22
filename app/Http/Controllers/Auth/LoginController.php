<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class LoginController
{
    public function __invoke(Request $request): View
    {
        return view('auth.login');
    }
}
