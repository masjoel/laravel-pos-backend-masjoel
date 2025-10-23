<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('userlog', function (Blueprint $table) {
            $table->id();
            $table->integer('iduser');
            $table->string('nama');
            $table->string('level', 50)->nullable();
            $table->string('do')->nullable();
            $table->timestamp('datetime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('ipaddr', 50)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('userlog');
    }
};
