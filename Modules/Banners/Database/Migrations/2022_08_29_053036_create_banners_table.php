<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    public function up()
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('imagen_desktop')->nullable();
            $table->string('imagen_mobile')->nullable();
            $table->string('link')->nullable();
            $table->string('referencia')->nullable();
            $table->boolean('mostrar')->default(true);
            $table->boolean('destacar')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('banners');
    }
}
