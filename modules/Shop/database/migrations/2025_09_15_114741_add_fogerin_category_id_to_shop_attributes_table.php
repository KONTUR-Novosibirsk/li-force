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
        Schema::table('shop_attributes', function (Blueprint $table) {
            $table->foreignId('attribute_category_id')
                ->nullable()
                ->constrained('shop_attribute_categories')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shop_attributes', function (Blueprint $table) {
            $table->dropForeign('shop_attributes_attribute_category_id_foreign');
        });
    }
};
