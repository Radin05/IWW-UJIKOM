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
        Schema::create('keluargas', function (Blueprint $table) {
            $table->string('no_kk')->primary();
            $table->string('nama_keluarga');
            $table->string('alamat');
            $table->string('no_telp');
            $table->unsignedBigInteger('rt_id');
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
        Schema::dropIfExists('keluargas');
    }
};
