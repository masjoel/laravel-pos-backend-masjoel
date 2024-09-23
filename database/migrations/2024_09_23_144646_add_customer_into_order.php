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
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('user_id')->after('id')->nullable();
            $table->string('invoice')->after('user_id')->nullable();
            $table->string('customer')->after('invoice')->nullable();
            $table->string('nama_kasir')->after('customer')->nullable();
            $table->double('kembali')->after('total_hpp')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('invoice');
            $table->dropColumn('customer');
            $table->dropColumn('kembali');
            $table->dropColumn('nama_kasir');
        });
    }
};
