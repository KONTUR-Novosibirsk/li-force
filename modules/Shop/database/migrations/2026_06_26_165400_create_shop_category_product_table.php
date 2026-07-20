<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shop_category_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('category_id');

            $table->foreign('product_id')
                ->references('id')
                ->on('shop_products')
                ->onDelete('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('shop_categories')
                ->onDelete('cascade');

            $table->unique(['product_id', 'category_id']);
            $table->integer('sort')->default(1);
        });

        DB::statement("
            INSERT IGNORE INTO shop_category_product (product_id, category_id)
            SELECT id, category_id
            FROM shop_products
            WHERE category_id IS NOT NULL
        ");

        $duplicateAliases = DB::table('shop_products')
            ->select('alias')
            ->groupBy('alias')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('alias');

        foreach ($duplicateAliases as $alias) {
            $products = DB::table('shop_products')
                ->where('alias', $alias)
                ->orderBy('id')
                ->get();

            foreach ($products as $index => $product) {
                if ($index === 0) {
                    continue;
                }

                $newAlias = $alias . '-' . $product->id;

                while (
                DB::table('shop_products')
                    ->where('alias', $newAlias)
                    ->where('id', '!=', $product->id)
                    ->exists()
                ) {
                    $newAlias .= '-copy';
                }

                DB::table('shop_products')
                    ->where('id', $product->id)
                    ->update([
                        'alias' => $newAlias,
                    ]);
            }
        }

        if (Schema::hasColumn('shop_products', 'category_id')) {
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_NAME = 'shop_products'
                  AND COLUMN_NAME = 'category_id'
                  AND CONSTRAINT_SCHEMA = DATABASE()
                  AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            foreach ($foreignKeys as $fk) {
                DB::statement(
                    "ALTER TABLE shop_products DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}"
                );
            }

            Schema::table('shop_products', function (Blueprint $table) {
                $table->dropColumn('category_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('shop_category_product');

        Schema::table('shop_products', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable();
            $table->foreign('category_id')
                ->references('id')
                ->on('shop_categories')
                ->onDelete('cascade');
        });
    }
};
