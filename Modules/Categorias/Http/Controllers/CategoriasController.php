<?php

namespace Modules\Categorias\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Modules\Categorias\Models\Categoria;
use RealRashid\SweetAlert\Facades\Alert;
use stdClass;
use Symfony\Component\HttpFoundation\Response;

class CategoriasController extends Controller
{
    public function index()
    {
        $x['title']     = "Categorias";
        $x['data']      = Categoria::get();

        return view('categorias::index', $x);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'      => ['required', 'string', 'max:255']
            ,'nombre_web' => ['nullable', 'string', 'max:255']
            ,'imagen'     => ['nullable', 'string']
            ,'icono'      => ['nullable', 'string']
            ,'referencia' => ['nullable', 'string', 'max:255']
            ,'mostrar'    => ['sometimes', 'boolean']
            ,'destacar'   => ['sometimes', 'boolean']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        $categoria = new stdClass;
        $categoria->nombre = $request->name;
        try {
            $categoria = Categoria::create([
                'nombre'      => $request->nombre
                ,'nombre_web' => $request->nombre_web
                ,'imagen'     => $request->imagen
                ,'icono'      => $request->icono
                ,'referencia' => $request->referencia
                ,'mostrar'    => ($request->mostrar ? $request->mostrar : false)
                ,'destacar'   => ($request->destacar ? $request->destacar : false)
            ]);
            Alert::success('Información', 'Registro <b>' . $categoria->nombre . '</b> creado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Información', 'Registro <b>' . $categoria->nombre . '</b> error al crear : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function show(Request $request)
    {
        $categoria = Categoria::find($request->id);
        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Data categoria by id',
            'data'      => $categoria
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'u_nombre'      => ['required', 'string', 'max:255'],
            'u_nombre_web'  => ['nullable','string', 'max:255'],
            'u_imagen'      => ['nullable','string'],
            'u_icono'       => ['nullable','string'],
            'u_referencia'  => ['nullable','string', 'max:255'],
            'u_mostrar'     => ['sometimes', 'boolean'],
            'u_destacar'    => ['sometimes', 'boolean']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        try {
            $categoria = Categoria::find($request->id);
            $categoria->update([
                'nombre'      => $request->u_nombre,
                'nombre_web'  => $request->u_nombre_web,
                'imagen'      => $request->u_imagen,
                'icono'       => $request->u_icono,
                'referencia'  => $request->u_referencia,
                'mostrar'     => ($request->u_mostrar ? $request->u_mostrar : false),
                'destacar'    => ($request->u_destacar ? $request->u_destacar : false)
            ]);
            Alert::success('Aviso', 'Registro <b>' . $categoria->nombre . '</b> actualizado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Registro <b>' . $categoria->nombre . '</b> error al actualizar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function destroy(Request $request)
    {
        try {
            $categoria = Categoria::find($request->id);
            if($categoria->imagen) {
                if (file_exists(public_path($categoria->imagen))) {
                    unlink(public_path($categoria->imagen));
                }
            }
            if($categoria->icono) {
                if (file_exists(public_path($categoria->icono))) {
                    unlink(public_path($categoria->icono));
                }
            }
            $categoria->delete();
            Alert::success('Información', 'Registro <b>' . $categoria->nombre . '</b> eliminado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Información', 'Registro <b>' . $categoria->nombre . '</b> error al eliminar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    //Synchronize
    public function synchronize()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            exit;
        }
        $username='p4nt4L1to';
        $password='305pr15mA';
        $httpClient = new \GuzzleHttp\Client();
        $req = $httpClient->get('http://190.128.136.242:7575/catalogserv/categorias', ['auth' => [$username, $password]]);
        $res = $req->getBody();
        //return json_decode($res, true);
        //return count(json_decode($res,true));
        //dd($res);
        $categorias = json_decode($res, true);
        foreach ($categorias as $categoria) {
            $existe = null;
            $existe = Categoria::where('referencia', $categoria['id_categoria'])->first();
            if($existe == null) {
                Categoria::create([
                    'nombre'     => $categoria['nombre']
                    ,'referencia' => $categoria['id_categoria']
                    ,'mostrar'    => $categoria['estado'] == 'A' ? 1 : 0
                ]);                    
            }else {
                $existe->update([
                    'nombre'     => $categoria['nombre']
                    ,'mostrar'    => $categoria['estado'] == 'A' ? 1 : 0
                ]);
            }
        }
        $totalCategorias = Categoria::count();
        return "Ahora hay ".$totalCategorias." categorias en el sistema <a href=\"".route('categorias.index')."\">Volver</a>";

    }

    public function synchronizeDirect()
    {
        $username='p4nt4L1to';
        $password='305pr15mA';
        $httpClient = new \GuzzleHttp\Client();
        $req = $httpClient->get('http://190.128.136.242:7575/catalogserv/categorias', ['auth' => [$username, $password]]);
        $res = $req->getBody();
        //return json_decode($res, true);
        //return count(json_decode($res,true));
        //dd($res);
        $categorias = json_decode($res, true);
        foreach ($categorias as $categoria) {
            $existe = null;
            $existe = Categoria::where('referencia', $categoria['id_categoria'])->first();
            if($existe == null) {
                Categoria::create([
                    'nombre'     => $categoria['nombre']
                    ,'referencia' => $categoria['id_categoria']
                    ,'mostrar'    => $categoria['estado'] == 'A' ? 1 : 0
                ]);                    
            }else {
                $existe->update([
                    'nombre'     => $categoria['nombre']
                    ,'mostrar'    => $categoria['estado'] == 'A' ? 1 : 0
                ]);
            }
        }
        $totalCategorias = Categoria::count();
        return "Ahora hay ".$totalCategorias." categorias en el sistema <a href=\"".route('categorias.index')."\">Volver</a>";

    }

    public function synchronize_test() {
        Log::info('synchronize de categorias');
        return;
    }
}
