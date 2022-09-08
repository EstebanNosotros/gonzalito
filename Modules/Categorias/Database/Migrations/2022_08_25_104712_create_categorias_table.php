<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriasTable extends Migration
{
    public function up()
    {
        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('nombre_web')->nullable();
            $table->string('imagen')->nullable();
            $table->string('icono')->nullable();
            $table->string('referencia')->nullable();
            $table->boolean('mostrar')->default(true);
            $table->boolean('destacar')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('categorias');
    }
}
