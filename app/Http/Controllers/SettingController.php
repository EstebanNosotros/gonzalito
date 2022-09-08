<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class SettingController extends Controller
{
    public function index()
    {
        $x['title']     = 'Configuración';
        $x['category']  = Setting::select('category')->groupBy('category')->get();
        return view('admin.setting', $x);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'string'],
            'role'      => ['required']
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        try {
            $setting = Setting::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => bcrypt($request->password)
            ]);
            $setting->assignRole($request->role);
            Alert::success('Aviso', 'Dato <b>' . $setting->name . '</b> registrado exitosamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $setting->name . '</b> error al registrarlo : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function show(Request $request)
    {
        $setting = Setting::find($request->id);
        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Dato de configuración por id',
            'data'      => $setting
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key'       => ['required'],
            'value'     => ['required']
        ]);
        
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }

        try {
            for ($i = 0; $i < count($request->key); $i++) { 
                Setting::where(['key' => $request->key[$i]])->update(['value' => $request->value[$i]]);
            }
            Alert::success('Aviso', 'Configuración actualizada exitosamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Error al actualizar la configuración : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function destroy(Request $request)
    {
        try {
            $setting = Setting::find($request->id);
            $setting->delete();
            Alert::success('Aviso', 'Dato <b>' . $setting->name . '</b> eliminado exitosamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $setting->name . '</b> error al eliminarlo : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }
}
