<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->text('address')->nullable();
            $table->text('user_type')->nullable();
            $table->text('payment_type')->nullable();
            $table->text('is_pickup')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropColumn('address');
            $table->dropColumn('user_type');
            $table->dropColumn('payment_type');

        });
    }
};
