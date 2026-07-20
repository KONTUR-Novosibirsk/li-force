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
            $table->text('stock')->nullable();
            $table->text('gross')->nullable();
            $table->text('link_wb')->nullable();
            $table->text('link_ozon')->nullable();
            $table->text('scope_of_application')->nullable();
            $table->text('general_information')->nullable();
            $table->text('design_features')->nullable();
            $table->text('main_features')->nullable();
            $table->text('short_description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_products', function (Blueprint $table) {
            $table->dropColumn('stock');
            $table->dropColumn('gross');
            $table->dropColumn('link_wb');
            $table->dropColumn('link_ozon');
            $table->dropColumn('scope_of_application');
            $table->dropColumn('general_information');
            $table->dropColumn('design_features');
            $table->dropColumn('main_features');
            $table->dropColumn('short_description');
        });
    }
};
