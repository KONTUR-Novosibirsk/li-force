<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->integer('point_saby')->nullable()->default(null);
            $table->integer('price_list_saby')->nullable()->default(null);
        });
    }

    public function down(): void
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->dropColumn('point_saby');
            $table->dropColumn('price_list_saby');
        });
    }
};
