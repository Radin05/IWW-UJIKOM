<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('histori_kas_rw', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nominal'); // Jumlah kas (tanpa dikurangi pengeluaran)
            $table->date('tgl_pembaruan_kas'); // Tanggal pembaruan kas
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('histori_kas');
    }
};
