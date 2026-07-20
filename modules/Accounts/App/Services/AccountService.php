<?php

namespace Modules\Accounts\App\Services;

use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Modules\Accounts\App\Mail\AccountConfirmed;
use Modules\Accounts\App\Models\Account;

class AccountService
{
    public function accounts(array $filters): LengthAwarePaginator
    {
        return Account::query()
            ->filter($filters)
            ->orderBy('id', 'DESC')
            ->paginate(auth()->user()->per_page)
            ->withQueryString();
    }

    public function attemptSendMail(Account $account): array
    {
        try {
            Mail::to($account->email)->send(new AccountConfirmed($account));

            return ['message' => 'Оповещение отправлено'];
        } catch (Exception $e) {
            return ['message' => "$account->email недействителен. Отправка невозможна"];
        }
    }
}
