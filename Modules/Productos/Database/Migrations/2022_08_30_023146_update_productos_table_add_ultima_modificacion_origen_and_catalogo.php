<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProductosTableAddUltimaModificacionOrigenAndCatalogo extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->string('ultima_modificacion_origen')->nullable();
            $table->boolean('catalogo')->default(false);
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('ultima_modificacion_origen');
            $table->dropColumn('catalogo');
        });
    }
}
