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
        Schema::create('kas_rws', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembayaran_id')->nullable();
            $table->decimal('jumlah_kas_rw', 15, 2);
            $table->unsignedBigInteger('pengeluaran_kas_rw_id')->nullable();
            $table->unsignedBigInteger('uang_tambahan_kas_id')->nullable();
            $table->timestamps();

            $table->foreign('pengeluaran_kas_rw_id')->references('id')->on('pengeluaran_kas_rws');
            $table->foreign('uang_tambahan_kas_id')->references('id')->on('uang_tambahans')->onDelete('set null');
            $table->foreign('pembayaran_id')->references('id')->on('pembayarans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kas_rws');
    }
};
