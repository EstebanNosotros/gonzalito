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
            ,'cuotas'                 => ['nullable', 'string', 'max:4000']
            ,'productos_relacionados' => ['nullable', 'string']
            ,'referencia'             => ['nullable', 'string']
            ,'mostrar'                => ['sometimes', 'boolean']
            ,'destacar'               => ['sometimes', 'boolean']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
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
                ,'cuotas'                 => $request->cuotas
                ,'productos_relacionados' => $request->productos_relacionados
                ,'referencia'             => $request->referencia
                ,'mostrar'                => ($request->mostrar ? $request->mostrar : false)
                ,'destacar'               => ($request->destacar ? $request->destacar : false)
            ]);
            Alert::success('Aviso', 'Dato <b>' . $producto->nombre . '</b> registrado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $producto->nombre . '</b> error al registrar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function show(Request $request)
    {
        $producto = Producto::with('categoria')->where('id', $request->id)->first();
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
            ,'u_cuotas'                 => ['nullable', 'string', 'max:4000']
            ,'u_productos_relacionados' => ['nullable', 'string']
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
                ,'cuotas'                 => $request->u_cuotas
                ,'productos_relacionados' => $request->u_productos_relacionados
                ,'referencia'             => $request->u_referencia
                ,'mostrar'                => ($request->u_mostrar ? $request->u_mostrar : false)
                ,'destacar'               => ($request->u_destacar ? $request->u_destacar : false)
            ]);
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
