<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAvisosTable extends Migration
{
    public function up()
    {
        Schema::create('avisos', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->longText('cuerpo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('avisos');
    }
}
