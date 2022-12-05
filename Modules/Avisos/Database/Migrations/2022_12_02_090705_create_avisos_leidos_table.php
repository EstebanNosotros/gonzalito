<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvisosLeidosTable extends Migration
{
    public function up()
    {
        Schema::create('avisos_leidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('aviso_id');
            $table->foreign('aviso_id')
                  ->references('id')->on('avisos')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('avisos_leidos');
    }
}
