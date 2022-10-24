<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductoImagenesTable extends Migration
{
    public function up()
    {
        Schema::create('producto_imagenes', function (Blueprint $table) {
            $table->id();
            $table->string('imagen');
            $table->unsignedBigInteger('producto_id');
            $table->foreign('producto_id')
                  ->references('id')->on('productos')->onDelete('cascade');
            $table->string('referencia')->nullable();
            $table->boolean('mostrar')->default(true);
            $table->boolean('destacar')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('producto_imagenes');
    }
}
