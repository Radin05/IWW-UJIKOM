<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('uang_tambahan_rts', function (Blueprint $table) {
            $table->id();
            $table->decimal('nominal', 15, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('rt_id')->nullable();
            $table->timestamps();

            $table->foreign('rt_id')->references('id')->on('rts')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('uang_tambahan_rts');
    }
};
