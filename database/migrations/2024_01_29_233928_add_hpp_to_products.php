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
        Schema::table('products', function (Blueprint $table) {
            $table->double('hpp')->after('description')->default(0);
            // $table->integer('stock_min')->after('stock')->default(1);
            // $table->boolean('is_stock')->after('stock_min')->default(1);
            // $table->boolean('publish')->after('is_stock')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('hpp');
            // $table->dropColumn('stock_min');
            // $table->dropColumn('is_stock');
            // $table->dropColumn('publish');
        });
    }
};
