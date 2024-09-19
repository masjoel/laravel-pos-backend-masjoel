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
            $table->integer('user_id')->after('id')->nullable();
            $table->integer('product_id')->after('user_id')->nullable();
            $table->string('category')->nullable()->change();
            $table->integer('category_id')->after('category')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('product_id');
            $table->dropColumn('category_id');
            $table->enum('category', ['food', 'drink', 'snack'])->collation('utf8mb4_unicode_ci')->change();
        });
    }
};
