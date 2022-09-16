<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Banners\Models\Banner;
use Modules\Productos\Models\Producto;
use Modules\Categorias\Models\Categoria;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $x['categorias']           = Categoria::get();
        $x['categorias_populares'] = Categoria::where('destacar', true)->take(12);
        $x['productos_nuevos']     = Producto::orderBy('created_at', 'desc')->take(12);
        $x['productos_ofertas']    = Producto::where('destacar', true)->take(12);
        $x['banner_principal']     = Banner::orderBy('created_at', 'desc')->first();
        return view('home', $x);
    }
}
