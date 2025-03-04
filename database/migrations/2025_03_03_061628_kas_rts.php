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
        Schema::create('kas_rts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rt_id')->nullable();
            $table->unsignedBigInteger('pembayaran_id')->nullable();
            $table->unsignedBigInteger('pengeluaran_kas_rt_id')->nullable();
            $table->decimal('jumlah_kas_rt', 15, 2);
            $table->timestamps();

            $table->foreign('pengeluaran_kas_rt_id')->references('id')->on('pengeluaran_kas_rts');
            $table->foreign('rt_id')->references('id')->on('rts')->onDelete('cascade');
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
        Schema::dropIfExists('kas_rts');
    }
};
