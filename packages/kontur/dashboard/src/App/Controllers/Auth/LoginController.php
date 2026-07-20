<?php

namespace Kontur\Dashboard\App\Controllers\Auth;

use Illuminate\Http\Request;

class LoginController extends \App\Http\Controllers\Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): \Inertia\Response
    {
        return inertia('Modules/Dashboard/Auth/Login');
    }
}
