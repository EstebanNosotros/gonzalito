<?php

namespace Modules\Vendedores\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Modules\Vendedores\Models\Vendedor;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;
use DB;

class VendedoresController extends Controller
{
    public function index()
    {
        $x['title'] = "Vendedor";
        $x['data']  = DB::connection('mysql_catalogo')
                        ->select('SELECT u.id, u.nombre, u.ci, u.contacto, u.key, u.referencia, u.updated_at
                                    FROM users u');

        return view('vendedores::index', $x);
    }

    /*public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'      => ['required', 'string', 'max:255']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        try {
            $vendedore = Vendedore::create([
                'nombre'      => $request->nombre
            ]);
            Alert::success('Aviso', 'Dato <b>' . $vendedore->nombre . '</b> registrado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $vendedore->nombre . '</b> error al registrar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }*/

    public function show(Request $request)
    {
        $vendedor = Vendedor::find($request->id);
        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Dato de vendedor por id',
            'data'      => $vendedor
        ], Response::HTTP_OK);
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
        $req = $httpClient->get('http://190.128.136.242:7575/catalogserv/vendedores', ['auth' => [$username, $password]]);
        $res = $req->getBody();
        //return json_decode($res, true);
        //return count(json_decode($res,true));
        //dd($res);
        $c = 0;
        $vendedores = json_decode($res, true);
        foreach ($vendedores as $vendedor) {
            $existe = null;
            $existe = DB::connection('mysql_catalogo')
                        ->select('SELECT u.id, u.nombre, u.ci, u.contacto, u.key, u.referencia, u.updated_at
                                    FROM users u
                                   WHERE u.nombre = :nombre', ['nombre' => $vendedor['nombre']]);
            if($existe == null) {
                // $existe = DB::connection('mysql_catalogo')
                //             ->table('users')
                //             ->insert([
                //                 array(
                //                     'nombre' => vendedor['nombre']
                //                     ,'ci'    => 
                //                 )
                //             ]);                    
                null;
            }else {
                // $existe = $existe[0];
                $c++;
                DB::connection('mysql_catalogo')
                  ->table('users')
                  ->where('nombre', $vendedor['nombre'])
                  ->update([
                        'referencia'  => $vendedor['id_vendedor']
                        // ,'contacto'   => $vendedor['contacto']
                  ]);     
            }
        }
        return "Vendedores de catalogo actualizados en el sistema, se actualizaron ".$c." registros <a href=\"".route('vendedores.index')."\">Volver</a>";

    }

   /* public function synchronizeDirect()
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
    }*/

    /*public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'      => ['required', 'string', 'max:255']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        try {
            $vendedore = Vendedore::find($request->id);
            $vendedore->update([
                'nombre'  => $request->nombre
            ]);
            Alert::success('Aviso', 'Dato <b>' . $vendedore->nombre . '</b> actualizado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $vendedore->nombre . '</b> error al actualizar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function destroy(Request $request)
    {
        try {
            $vendedore = Vendedore::find($request->id);
            $vendedore->delete();
            Alert::success('Aviso', 'Dato <b>' . $vendedore->nombre . '</b> eliminado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $vendedore->nombre . '</b> error al eliminar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }*/
}
