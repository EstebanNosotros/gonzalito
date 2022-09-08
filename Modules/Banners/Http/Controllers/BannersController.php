<?php

namespace Modules\Banners\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;
use Modules\Banners\Models\Banner;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class BannersController extends Controller
{
    public function index()
    {
        $x['title']     = "Banners";
        $x['data']      = Banner::get();

        return view('banners::index', $x);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre'          => ['required', 'string', 'max:255']
            ,'imagen_desktop' => ['nullable', 'string', 'max:255']
            ,'imagen_mobile'  => ['nullable', 'string', 'max:255']
            ,'referencia'     => ['nullable', 'string', 'max:255']
            ,'link'           => ['nullable', 'string', 'max:255']
            ,'mostrar'        => ['nullable', 'boolean']
            ,'destacar'       => ['nullable', 'boolean']
            ,'tipo'           => ['sometimes', Rule::in(['Principal','Secundario'])]
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        try {
            $banner = Banner::create([
                'nombre'          => $request->nombre
                ,'imagen_desktop' => $request->imagen_desktop
                ,'imagen_mobile'  => $request->imagen_mobile
                ,'referencia'     => $request->referencia
                ,'link'           => $request->link
                ,'mostrar'        => ($request->mostrar ? $request->mostrar : false)
                ,'destacar'       => ($request->destacar ? $request->destacar : false)
                ,'tipo'           => $request->tipo
            ]);
            Alert::success('Aviso', 'Dato <b>' . $banner->nombre . '</b> registrado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $banner->nombre . '</b> error al registrar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function show(Request $request)
    {
        $banner = Banner::find($request->id);
        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Dato banner por id',
            'data'      => $banner
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'u_nombre'          => ['required', 'string', 'max:255']
            ,'u_imagen_desktop' => ['nullable', 'string', 'max:255']
            ,'u_imagen_mobile'  => ['nullable', 'string', 'max:255']
            ,'u_referencia'     => ['nullable', 'string', 'max:255']
            ,'u_link'           => ['nullable', 'string', 'max:255']
            ,'u_mostrar'        => ['nullable', 'boolean']
            ,'u_destacar'       => ['nullable', 'boolean']
            ,'u_tipo'           => ['sometimes', Rule::in(['Principal','Secundario'])]
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        try {
            $banner = Banner::find($request->id);
            $banner->update([
                'nombre'          => $request->u_nombre
                ,'imagen_desktop' => $request->u_imagen_desktop
                ,'imagen_mobile'  => $request->u_imagen_mobile
                ,'referencia'     => $request->u_referencia
                ,'link'           => $request->u_link
                ,'mostrar'        => ($request->u_mostrar ? $request->u_mostrar : false)
                ,'destacar'       => ($request->u_destacar ? $request->u_destacar : false)
                ,'tipo'           => $request->u_tipo
            ]);
            Alert::success('Aviso', 'Dato <b>' . $banner->nombre . '</b> actualizado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $banner->nombre . '</b> error al actualizar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function destroy(Request $request)
    {
        try {
            $banner = Banner::find($request->id);
            if (file_exists(public_path($banner->imagen_desktop))) {
                unlink(public_path($banner->imagen_desktop));
            }
            if (file_exists(public_path($banner->imagen_mobile))) {
                unlink(public_path($banner->imagen_mobile));
            }
            $banner->delete();
            Alert::success('Aviso', 'Dato <b>' . $banner->nombre . '</b> eliminado correctamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $banner->nombre . '</b> error al eliminar : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }
}
