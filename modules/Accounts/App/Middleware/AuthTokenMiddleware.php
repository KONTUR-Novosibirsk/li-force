<?php

namespace Modules\Accounts\App\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        if (! $account = current_auth_user()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            return redirect()->route('account.register');
        }

        $request->merge(['account' => $account]);

        return $next($request);
    }
}
