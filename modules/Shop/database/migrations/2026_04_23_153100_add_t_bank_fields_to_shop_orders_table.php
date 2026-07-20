<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->string('t_bank_id')->nullable()->default(null);
            $table->boolean('t_bank_status')->nullable()->default(0);
        });
    }

    public function down()
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropColumn('t_bank_id');
            $table->dropColumn('t_bank_status');
        });
    }
};
