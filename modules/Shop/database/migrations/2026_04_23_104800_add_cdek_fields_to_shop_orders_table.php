<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->string('cdek_uuid')->nullable()->default(null);
            $table->string('cdek_status')->nullable()->default(null);
        });
    }

    public function down()
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropColumn('cdek_uuid');
            $table->dropColumn('cdek_status');
        });
    }
};
