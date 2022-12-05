<?php

namespace Modules\Avisos\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Modules\Avisos\Models\Aviso;
use Modules\Avisos\Models\AvisoLeido;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;
use DB;

class AvisosController extends Controller
{
    public function index()
    {
        $x['title']     = "Avisos";
        $x['data']      = Aviso::get();

        return view('avisos::index', $x);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titulo'  => ['required', 'string', 'max:255']
            ,'cuerpo' => ['required', 'string']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        try {
            $aviso = Aviso::create([
                'titulo'  => $request->titulo
                ,'cuerpo' => $request->cuerpo
            ]);
            Alert::success('Aviso', 'Dato <b>' . $aviso->titulo . '</b> registrado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $aviso->titulo . '</b> error al registrar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function show(Request $request)
    {
        $aviso = Aviso::with('leidos')->find($request->id);
        $leidos = $aviso->leidos;
        $usuarios = [];
        $usersId = [];
        foreach($leidos as $leido) {
            array_push($usersId, $leido->user_id);
        }
        // $usuarios = DB::connection('mysql_catalogo')
        //               ->select('SELECT u.nombre
        //                           FROM users u
        //                          WHERE u.id IN :usersId', ['usersId' => $usersId]);
        $usuarios = DB::connection('mysql_catalogo')
                      ->table('users')
                      ->whereIn('id', $usersId)->pluck('nombre');
        // $aviso->push($usuarios);
        $aviso->usuarios = $usuarios;
        \Log::info($aviso);
        // $usuarios = DB::connection('mysql_catalogo')
        //               ->select('SELECT d.*, u.nombre
        //                           FROM dispositivos d, users u
        //                          WHERE u.id = d.user_id');
        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Dato aviso por id',
            'data'      => $aviso
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'u_titulo'    => ['required', 'string', 'max:255']
            ,'u_cuerpo' => ['required', 'string']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        try {
            $aviso = Aviso::find($request->id);
            $aviso->update([
                'titulo'  => $request->u_titulo
                ,'cuerpo' => $request->u_cuerpo
            ]);
            Alert::success('Aviso', 'Dato <b>' . $aviso->titulo . '</b> actualizado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $aviso->titulo . '</b> error al actualizar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function destroy(Request $request)
    {
        try {
            $aviso = Aviso::find($request->id);
            $aviso->delete();
            Alert::success('Aviso', 'Dato <b>' . $aviso->titulo . '</b> eliminado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $aviso->titulo . '</b> error al eliminar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function leido(Request $request)
    {
        if ($request->ajax()) {
            try {
                $aviso  = Aviso::find($request->aviso_id);
                $existe = '';
                // $existe = AvisoLeido::where()
                // AvisoLeido::create([

                // ]); 
                Alert::success('Aviso', 'Dato <b>' . $aviso->titulo . '</b> eliminado correctamente')->toToast()->toHtml();
            } catch (\Throwable $th) {
                Alert::error('Aviso', 'Dato <b>' . $aviso->titulo . '</b> error al eliminar : ' . $th->getMessage())->toToast()->toHtml();
            }
        }
        return back();
    }
}
