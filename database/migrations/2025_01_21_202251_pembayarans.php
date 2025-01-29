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
        Schema::create('pembayarans', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk_keluarga')->nullable();
            $table->decimal('sejumlah', 15, 2);
            $table->date('tgl_pembayaran');
            $table->integer('year');
            $table->integer('month');
            $table->timestamps();

            $table->foreign('no_kk_keluarga')
                ->references('no_kk')
                ->on('keluargas')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pembayarans');
    }
};
