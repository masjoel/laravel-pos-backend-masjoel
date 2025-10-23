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
        Schema::create('profil_usahas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_client')->nullable();
            $table->string('alamat_client')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kabupaten')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kades')->nullable();
            $table->string('sekretaris')->nullable();
            $table->string('bendahara')->nullable();
            $table->string('logo')->nullable();
            $table->string('photo')->nullable();
            $table->string('image_icon')->nullable();
            $table->text('peta')->nullable();
            $table->integer('kelurahan_id')->nullable();
            $table->integer('kecamatan_id')->nullable();
            $table->integer('kabupaten_id')->nullable();
            $table->integer('provinsi_id')->nullable();
            $table->string('urlserver')->nullable();
            $table->string('nama_app')->nullable();
            $table->string('desc_app')->nullable();
            $table->string('develop')->nullable();
            $table->string('web')->nullable();
            $table->string('mcad')->nullable();
            $table->string('init')->nullable();
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->string('kodedesa')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('twitter')->nullable();
            $table->string('facebook')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();
            $table->string('apikey')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profil_usahas');
    }
};
