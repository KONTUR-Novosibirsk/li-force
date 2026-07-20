<?php

namespace Modules\Accounts\App\Controllers\Public;

use App\Http\Controllers\Controller;
use Galtsevt\LaravelSeo\App\Facades\Seo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Modules\Accounts\App\Models\Account;
use Modules\Accounts\App\Requests\LoginRequest;
use Modules\Accounts\App\Requests\RegisterRequest;
use Modules\Accounts\App\Resources\AccountResource;
use Modules\Accounts\App\Services\AuthService;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function register(): Response
    {
        Seo::breadcrumbs()->add('Регистрация', route('account.register'));

        return response()->view('accounts::register');
    }

    public function login(): Response
    {
        Seo::breadcrumbs()->add('Авторизация', route('account.login'));

        return response()->view('accounts::login');
    }

    public function store(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $account = Account::create($validated);
        $account->login(true);

        return response()->json(new AccountResource($account), 201);
    }

    public function authenticate(LoginRequest $request): JsonResponse
    {
        $account = $this->authService->attemptLogin($request->validated());

        if (! $account) {
            return response()->json(['message' => 'Неверные учетные данные'], 401);
        }

        return response()->json(new AccountResource($account));
    }
}
