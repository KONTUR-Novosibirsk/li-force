<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->bigInteger('ozon_id')->nullable()->after('id');
            $table->bigInteger('wb_id')->nullable()->after('ozon_id');
        });
    }

    public function down(): void
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->dropColumn('ozon_id');
            $table->dropColumn('wb_id');
        });
    }
};
