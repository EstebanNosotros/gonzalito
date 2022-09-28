<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProductosTableAddStockUltActualizacion extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->boolean('en_stock')->default(true);
            $table->timestamp('ultima_sincronizacion')->nullable();
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('en_stock');
            $table->dropColumn('ultima_sincronizacion');
        });
    }
}
