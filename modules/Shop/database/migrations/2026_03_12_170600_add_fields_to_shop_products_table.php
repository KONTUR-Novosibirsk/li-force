<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->string('article')->nullable()->after('alias');
            $table->string('set')->nullable()->after('article');
            $table->string('warranty')->nullable()->after('set');
        });
    }

    public function down(): void
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->dropColumn('article');
            $table->dropColumn('set');
            $table->dropColumn('warranty');
        });
    }
};
