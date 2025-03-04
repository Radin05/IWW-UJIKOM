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
        Schema::create('pengeluaran_kas_rts', function (Blueprint $table) {
            $table->id();
            $table->decimal('nominal', 15, 2)->nullable();
            $table->foreignId('kegiatan_id')->nullable()->constrained('kegiatan_rts')->onDelete('cascade');
            $table->text('keterangan')->nullable();
            $table->date('tgl_pengeluaran');
            $table->unsignedBigInteger('rt_id')->nullable();
            $table->timestamps();

            $table->foreign('rt_id')->references('id')->on('rts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengeluaran_kas_rts');
    }
};

