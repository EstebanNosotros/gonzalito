<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProductosTableAddEnOfertaAndPrecioOfertaFields extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->boolean('en_oferta')->default(false);
            $table->double('precio_oferta', 15, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('precio_oferta');
            $table->dropColumn('en_oferta');
        });
    }
}
