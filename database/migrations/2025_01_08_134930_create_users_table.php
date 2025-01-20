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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('no_kk_keluarga')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('role')->default('user')->nullable();
            $table->unsignedBigInteger('rt_id')->nullable();
            $table->rememberToken();
            $table->timestamps();

            // Tambahkan foreign key dengan benar
            $table->foreign('no_kk_keluarga')->references('no_kk')->on('keluargas');
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
        Schema::dropIfExists('users');
    }
};
