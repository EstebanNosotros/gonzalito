<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateProductosTableAddCuotas extends Migration
{
    public function up()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->longText('cuotas')->nullable();
        });
    }

    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn('cuotas');
        });
    }
}
