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

    public function synchronize(Request $request)
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
