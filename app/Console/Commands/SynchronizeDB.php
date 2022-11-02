<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Banners\Http\Controllers\BannersController;
use Modules\Categorias\Http\Controllers\CategoriasController;
use Modules\Productos\Http\Controllers\ProductosController;
use Illuminate\Support\Facades\Log;

class SynchronizeDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'synchronize:db';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza base de datos de backend con fuente de datos Gonzalito';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $categoriasController = new CategoriasController;
        $confirmCategoria     = $categoriasController->synchronizeDirect();
        Log::info($confirmCategoria);
        echo $confirmCategoria;
        $productosController  = new ProductosController;
        $confirmProducto      = $productosController->synchronizeDirect();
        Log::info($confirmProducto);
        echo $confirmProducto;
    }
}
