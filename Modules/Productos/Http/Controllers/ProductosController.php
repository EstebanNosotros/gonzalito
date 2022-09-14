<?php

namespace Modules\Productos\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Modules\Productos\Models\Producto;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;
use Modules\Categorias\Models\Categoria;
use Modules\Productos\Models\ProductoCuota;
use stdClass;

class ProductosController extends Controller
{
    public function index()
    {
        $x['title']      = "Productos";
        $x['data']       = Producto::get();
        $x['categorias'] = Categoria::get();

        return view('productos::index', $x);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'                  => ['required', 'string', 'max:255']
            ,'nombre_web'             => ['nullable', 'string', 'max:255']
            ,'descripcion'            => ['nullable', 'string', 'max:4000']
            ,'codigo'                 => ['nullable', 'string']
            ,'precio'                 => ['nullable', 'numeric']
            ,'marca'                  => ['nullable', 'string']
            ,'categoria_id'           => ['required', 'integer', 'exists:categorias,id']
            ,'tags'                   => ['nullable', 'string', 'max:4000']
            ,'imagen_principal'       => ['nullable', 'string']
           // ,'cuotas'                 => ['nullable', 'array']
           // ,'cuotas.*.cantidad'      => ['sometimes', 'numeric']
           // ,'cuotas.*.monto'         => ['sometimes', 'numeric']
            ,'productos_relacionados' => ['nullable', 'array']
            ,'referencia'             => ['nullable', 'string']
            ,'mostrar'                => ['sometimes', 'boolean']
            ,'destacar'               => ['sometimes', 'boolean']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        $producto = new stdClass;
        $producto->nombre = $request->nombre;
        try {
            $producto = Producto::create([
                'nombre'                  => $request->nombre
                ,'nombre_web'             => $request->nombre_web
                ,'descripcion'            => $request->descripcion
                ,'codigo'                 => $request->codigo
                ,'precio'                 => $request->precio
                ,'marca'                  => $request->marca
                ,'categoria_id'           => $request->categoria_id
                ,'tags'                   => $request->tags
                ,'imagen_principal'       => $request->imagen_principal
                ,'productos_relacionados' => $request->productos_relacionados ? implode(',', $request->productos_relacionados) : ''
                ,'referencia'             => $request->referencia
                ,'mostrar'                => ($request->mostrar ? $request->mostrar : false)
                ,'destacar'               => ($request->destacar ? $request->destacar : false)
            ]);
            $tope = $request->crear_cuotas_tope;
            for ($i = 1; $i <= $tope; $i++) {
                if (isset($_POST["crear_cuotas_cantidad".$i])) {
                    $productoCuota = ProductoCuota::create([
                        'cuotas'       => $_POST["crear_cuotas_cantidad".$i]
                        ,'monto'       => $_POST["crear_cuotas_monto".$i]
                        ,'producto_id' => $producto->id
                    ]);
                }
            }
            Alert::success('Aviso', 'Dato <b>' . $producto->nombre . '</b> registrado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $producto->nombre . '</b> error al registrar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function show(Request $request)
    {
        $producto = Producto::with(['categoria', 'cuotas'])->where('id', $request->id)->first();
        //\Log::info($request->id);
        //dd($producto);
        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Dato producto por id',
            'data'      => $producto
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'u_nombre'                  => ['required', 'string', 'max:255']
            ,'u_nombre_web'             => ['nullable', 'string', 'max:255']
            ,'u_descripcion'            => ['nullable', 'string', 'max:4000']
            ,'u_codigo'                 => ['nullable', 'string']
            ,'u_precio'                 => ['nullable', 'numeric']
            ,'u_marca'                  => ['nullable', 'string']
            ,'u_categoria_id'           => ['required', 'integer', 'exists:categorias,id']
            ,'u_tags'                   => ['nullable', 'string', 'max:4000']
            ,'u_imagen_principal'       => ['nullable', 'string']
            //,'u_cuotas'                 => ['nullable', 'array']
            //,'u_cuotas.*.cantidad'      => ['sometimes', 'numeric']
            //,'u_cuotas.*.monto'         => ['sometimes', 'numeric']
            ,'u_productos_relacionados' => ['nullable', 'array']
            ,'u_referencia'             => ['nullable', 'string']
            ,'u_mostrar'                => ['sometimes', 'boolean']
            ,'u_destacar'               => ['sometimes', 'boolean']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        try {
            $producto = Producto::find($request->id);
            $producto->update([
                'nombre'                  => $request->u_nombre
                ,'nombre_web'             => $request->u_nombre_web
                ,'descripcion'            => $request->u_descripcion
                ,'codigo'                 => $request->u_codigo
                ,'precio'                 => $request->u_precio
                ,'marca'                  => $request->u_marca
                ,'categoria_id'           => $request->u_categoria_id
                ,'tags'                   => $request->u_tags
                ,'imagen_principal'       => $request->u_imagen_principal
                //,'cuotas'                 => $request->u_cuotas
                ,'productos_relacionados' => $request->u_productos_relacionados ? implode(',', $request->u_productos_relacionados) : ''
                ,'referencia'             => $request->u_referencia
                ,'mostrar'                => ($request->u_mostrar ? $request->u_mostrar : false)
                ,'destacar'               => ($request->u_destacar ? $request->u_destacar : false)
            ]);

            
            $cuotas = $producto->cuotas;
            $indice = $request->actualizar_cuotas_indice;
            $tope   = $request->actualizar_cuotas_tope;

            ////Intento de complicacion para guardar sin tener que recrear cada vez... dejo las anotaciones para quien tenga el valor

            /*\Log::info('indice: '.$request->actualizar_cuotas_indice);
            \Log::info('tope: '.$request->actualizar_cuotas_tope);
            \Log::info('cuotas: '.$producto->cuotas);
            \Log::info('cuotas-count: '.$producto->cuotas->count());
            if($cuotas->count() == $tope) {
                for ($i = 0; $i < $tope; $i++) {
                    if(isset($_POST["actualizar_cuotas_cantidad".($i+1)])) {
                        if($cuotas[$i]->cuotas != $_POST["actualizar_cuotas_cantidad".($i+1)]) {
                            $cuotas[$i]->cuotas = $_POST["actualizar_cuotas_cantidad".($i+1)];
                        }
                        if($cuotas[$i]->monto != $_POST["actualizar_cuotas_monto".($i+1)]) {
                            $cuotas[$i]->monto = $_POST["actualizar_cuotas_monto".($i+1)];
                        }
                        if($cuotas[$i]->cuotas > 1 && $cuotas[$i]->monto > 0) {
                            $cuotas[$i]->save();
                        }else {
                            $cuotas[$i]->delete();
                        }
                    }
                }
            }elseif ($cuotas->count() < $tope) {
                for ($i = 0; $i < $tope; $i++) {
                    if(isset($_POST["actualizar_cuotas_cantidad".($i+1)])) {
                        if(isset($cuotas[$i])) {
                            if($cuotas[$i]->cuotas != $_POST["actualizar_cuotas_cantidad".($i+1)]) {
                                $cuotas[$i]->cuotas = $_POST["actualizar_cuotas_cantidad".($i+1)];
                            }
                            if($cuotas[$i]->monto != $_POST["actualizar_cuotas_monto".($i+1)]) {
                                $cuotas[$i]->monto = $_POST["actualizar_cuotas_monto".($i+1)];
                            }
                            if($cuotas[$i]->cuotas > 1 && $cuotas[$i]->monto > 0) {
                                $cuotas[$i]->save();
                            }else {
                                $cuotas[$i]->delete();
                            }
                        }else {
                            if($_POST["actualizar_cuotas_cantidad".($i+1)] > 1 && $_POST["actualizar_cuotas_monto".($i+1)] > 0) {
                                ProductoCuota::create([
                                    'cuotas'       => $_POST["actualizar_cuotas_cantidad".($i+1)]
                                    ,'monto'       => $_POST["actualizar_cuotas_monto".($i+1)]
                                    ,'producto_id' => $producto->id
                                ]);
                            }
                        }
                    }else {
                        \Log::info('no esta seteado');
                        if(isset($_POST["actualizar_cuotas_cantidad".($i+1)])) {
                            \Log::info('cantidad a insertar: '. $_POST["actualizar_cuotas_cantidad".($i+1)]);
                            \Log::info('monto a insertar: '. $_POST["actualizar_cuotas_monto".($i+1)]);
                            if($_POST["actualizar_cuotas_cantidad".($i+1)] > 1 && $_POST["actualizar_cuotas_monto".($i+1)] > 0) {
                                \Log::info('deberia crear ahora');
                                ProductoCuota::create([
                                    'cuotas'       => $_POST["actualizar_cuotas_cantidad".($i+1)]
                                    ,'monto'       => $_POST["actualizar_cuotas_monto".($i+1)]
                                    ,'producto_id' => $producto->id
                                ]);
                            }
                        }
                    }
                }
            }else {
                foreach($cuotas as $cuota) {
                    $cuota->delete();   
                }
                for ($i = 0; $i < $tope; $i++) {
                    if(isset($_POST["actualizar_cuotas_cantidad".($i+1)])) {
                        if($_POST["actualizar_cuotas_cantidad".($i+1)] > 1 && $_POST["actualizar_cuotas_monto".($i+1)]) {
                            ProductoCuota::create([
                                'cuotas'       => $_POST["actualizar_cuotas_cantidad".($i+1)]
                                ,'monto'       => $_POST["actualizar_cuotas_monto".($i+1)]
                                ,'producto_id' => $producto->id
                            ]);
                        }
                    }
                }
            }*/

            ///Cuotas
            foreach($cuotas as $cuota) {
                $cuota->delete();   
            }
            for ($i = 0; $i < $tope; $i++) {
                if(isset($_POST["actualizar_cuotas_cantidad".($i+1)])) {
                    if($_POST["actualizar_cuotas_cantidad".($i+1)] > 1 && $_POST["actualizar_cuotas_monto".($i+1)] > 0) {
                        ProductoCuota::create([
                            'cuotas'       => $_POST["actualizar_cuotas_cantidad".($i+1)]
                            ,'monto'       => $_POST["actualizar_cuotas_monto".($i+1)]
                            ,'producto_id' => $producto->id
                        ]);
                    }
                }
            }

            Alert::success('Aviso', 'Dato <b>' . $producto->nombre . '</b> actualizado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $producto->nombre . '</b> error al actualizar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function destroy(Request $request)
    {
        try {
            $producto = Producto::find($request->id);
            if (file_exists(public_path($producto->imagen_principal))) {
                unlink(public_path($producto->imagen_principal));
            }
            $producto->delete();
            Alert::success('Aviso', 'Dato <b>' . $producto->nombre . '</b> eliminado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $producto->nombre . '</b> error al eliminar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }
}
