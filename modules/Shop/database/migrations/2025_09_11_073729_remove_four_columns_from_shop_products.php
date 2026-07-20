<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->dropColumn('general_information');
            $table->dropColumn('design_features');
            $table->dropColumn('main_features');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->text('general_information')->nullable();
            $table->text('design_features')->nullable();
            $table->text('main_features')->nullable();
        });
    }
};
