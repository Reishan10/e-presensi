<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->string('nik');
            $table->date('tgl_presensi');
            $table->time('jam_masuk');
            $table->time('jam_keluar');
            $table->string('foto_masuk');
            $table->string('foto_keluar');
            $table->string('lokasi_masuk');
            $table->string('lokasi_keluar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presensi');
    }
};
