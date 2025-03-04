<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kegiatan_rts', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->text('deskripsi')->nullable();
            $table->date('tanggal_kegiatan');
            $table->time('jam_kegiatan');
            $table->enum('status', ['Rapat', 'Kerja bakti', 'Kegiatan', 'Sudah selesai']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kegiatan_rts');
    }
};
