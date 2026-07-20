<?php

namespace Modules\Shop\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Accounts\App\Models\Account;

class ViewedProduct extends Model
{
    use HasFactory;

    public $timestamps = false; // у нас только viewed_at

    protected $table = 'viewed_products';

    protected $fillable = ['account_id', 'product_id', 'viewed_at'];

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function product()
    {
        return $this->belongsTo(ShopProduct::class);
    }
}
