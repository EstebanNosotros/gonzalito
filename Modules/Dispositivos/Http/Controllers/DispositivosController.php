<?php

namespace Modules\Dispositivos\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Modules\Dispositivos\Models\Dispositivo;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;
use DB;

class DispositivosController extends Controller
{
    public function index()
    {
        $x['title'] = "Dispositivos";
        $x['data']  = DB::connection('mysql_catalogo')
                        ->select('SELECT d.*
                                    FROM dispositivos d');
        // $x['data']      = Dispositivo::get();

        return view('dispositivos::index', $x);
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
            $dispositivo = Dispositivo::create([
                'nombre'      => $request->nombre
            ]);
            Alert::success('Aviso', 'Dato <b>' . $dispositivo->nombre . '</b> registrado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $dispositivo->nombre . '</b> error al registrar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function show(Request $request)
    {
        $dispositivo = Dispositivo::find($request->id);
        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Dato dispositivo por id',
            'data'      => $dispositivo
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'      => ['required', 'string', 'max:255']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        try {
            $dispositivo = Dispositivo::find($request->id);
            $dispositivo->update([
                'nombre'  => $request->nombre
            ]);
            Alert::success('Aviso', 'Dato <b>' . $dispositivo->nombre . '</b> actualizado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $dispositivo->nombre . '</b> error al actualizar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }*/

    public function destroy(Request $request)
    {
        try {
            // $dispositivo = Dispositivo::find($request->id);
            // $dispositivo->delete();
            $requestId = $request->id;
            $confirm   = DB::connection('mysql_catalogo')
                           ->table('dispositivos')
                           ->where('id', $requestId)
                           ->delete();
                        //    ->select('DELETE
                        //                FROM dispositivos d
                        //               WHERE id = :requestId',['requestId' => $requestId]);
            Alert::success('Aviso', 'Dato <b>' . $request->nombre . '</b> eliminado correctamente')->toToast()->toHtml();        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $request->nombre . '</b> error al eliminar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }
}
