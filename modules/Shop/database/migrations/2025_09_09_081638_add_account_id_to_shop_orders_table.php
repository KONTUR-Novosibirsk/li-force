<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            if (! Schema::hasColumn('shop_orders', 'account_id')) {
                $table->foreignId('account_id')->after('id')->nullable()->constrained('accounts')->onDelete('cascade');
            }
        });
    }

    public function down()
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            if (Schema::hasColumn('shop_orders', 'account_id')) {
                $table->dropConstrainedForeignId('account_id');
            }
        });
    }
};
