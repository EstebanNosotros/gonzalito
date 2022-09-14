<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductosTable extends Migration
{
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('nombre_web')->nullable();
            $table->longText('descripcion')->nullable();
            $table->string('codigo')->nullable();
            $table->double('precio', 15, 2)->default(0);
            $table->string('marca')->nullable();
            $table->unsignedBigInteger('categoria_id');
            $table->foreign('categoria_id')
                  ->references('id')->on('categorias')->onDelete('cascade');
            $table->longText('tags')->nullable();
            $table->string('imagen_principal')->nullable();
            $table->longText('cuotas')->nullable();
            $table->string('productos_relacionados')->nullable();
            $table->string('referencia')->nullable();
            $table->boolean('mostrar')->default(true);
            $table->boolean('destacar')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('productos');
    }
}
