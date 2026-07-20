<?php

namespace Modules\Accounts\App\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'full_name' => $this->full_name,
            'last_name' => $this->last_name,
            'login' => $this->login,
            'phone' => $this->phone,
            'counterparty' => $this['counterparty'],
            'saby_id' => $this['saby_id'],
            'is_confirmed' => $this->is_confirmed,
            'order_count' => $this->orders->count(),
            'updated_at' => $this->updated_at->isoFormat('LLL'),
            'created_at' => $this->created_at->isoFormat('LLL'),
        ];
    }
}
