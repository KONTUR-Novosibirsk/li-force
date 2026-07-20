<?php

namespace Modules\Accounts\App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Laravel\Sanctum\HasApiTokens;
use Modules\Accounts\App\Mail\PasswordReset;
use Modules\Shop\App\Models\ShopOrder;

class Account extends Authenticatable implements CanResetPasswordContract
{
    use CanResetPassword, HasApiTokens, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'login',
        'is_confirmed',
        'full_name',
        'phone',
        'last_name',
        'counterparty'
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
        'is_confirmed' => 'boolean',
        'counterparty' => 'array'
    ];

    public function orders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ShopOrder::class, 'account_id', 'id');
    }

    public function toggleConfirmed(): self
    {
        return tap($this, fn ($model) => $model->update(['is_confirmed' => ! $model->is_confirmed]));
    }

    public function checkPassword(string $password): bool
    {
        return Hash::check($password, $this->password);
    }

    public function login(bool $remember = false): self
    {
        $this->tokens()->delete();

        $plainText = $this->createToken('auth')->plainTextToken;

        $plain = str_contains($plainText, '|') ? explode('|', $plainText, 2)[1] : $plainText;

        Session::put('auth', $plain);

        if ($remember) {
            Cookie::queue('remember_token', $plain, 60 * 24 * 30); // на 30 дней
        }

        return $this;
    }

    public function logout(): void
    {
        $this->tokens()->delete();

        Session::forget('auth');

        Cookie::queue(Cookie::forget('remember_token'));
    }

    public function scopeConfirmed(Builder $query): Builder
    {
        return $query->where('is_confirmed', true);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new PasswordReset($token));
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($query) use ($search) {
                $query->where('full_name', 'like', '%'.$search.'%')
                    ->orWhere('login', 'like', '%'.$search.'%')
                    ->orWhere('phone', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        })->when(isset($filters['active']) && in_array($filters['active'], [0, 1]), function ($query) use ($filters) {
            return $query->where('is_active', $filters['active']);
        });
    }
}
