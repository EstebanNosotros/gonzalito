<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductoCuotasTable extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('cuotas');
        });

        Schema::create('producto_cuotas', function (Blueprint $table) {
            $table->id();
            $table->integer('cuotas');
            $table->double('monto', 15, 2);
            $table->unsignedBigInteger('producto_id');
            $table->foreign('producto_id')
                  ->references('id')->on('productos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('producto_cuotas');

        Schema::table('productos', function (Blueprint $table) {
            $table->longText('cuotas')->nullable();
        });
    }
}
