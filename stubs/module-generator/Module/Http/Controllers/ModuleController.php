<?php

namespace Modules\{Module}\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Modules\{Module}\Models\{Model};
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class {Module}Controller extends Controller
{
    public function index()
    {
        $x['title']     = "{Model}";
        $x['data']      = {Model}::get();

        return view('{module}::index', $x);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'      => ['required', 'string', 'max:255']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        try {
            ${model} = {Model}::create([
                'nombre'      => $request->nombre
            ]);
            Alert::success('Aviso', 'Dato <b>' . ${model}->nombre . '</b> registrado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . ${model}->nombre . '</b> error al registrar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function show(Request $request)
    {
        ${model} = {Model}::find($request->id);
        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Dato {model} por id',
            'data'      => ${model}
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
            ${model} = {Model}::find($request->id);
            ${model}->update([
                'nombre'  => $request->nombre
            ]);
            Alert::success('Aviso', 'Dato <b>' . ${model}->nombre . '</b> actualizado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . ${model}->nombre . '</b> error al actualizar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function destroy(Request $request)
    {
        try {
            ${model} = {Model}::find($request->id);
            ${model}->delete();
            Alert::success('Aviso', 'Dato <b>' . ${model}->nombre . '</b> eliminado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . ${model}->nombre . '</b> error al eliminar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }
}
