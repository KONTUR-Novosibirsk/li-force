<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('job_title');
            $table->integer('rating')->nullable()->after('name');
            $table->string('source')->nullable()->after('rating');
            $table->string('marketplace_product_id')->nullable()->after('source');
            $table->string('name')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->string('job_title')->nullable()->after('name');
            $table->dropColumn(['rating', 'source', 'marketplace_product_id']);
            $table->string('name')->nullable(false)->change();
        });
    }
};
