<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class RoleController extends Controller
{
    public function index()
    {
        $x['title']         = 'Roles';
        $x['data']          = Role::with('permissions')->get();
        $x['permission']    = Permission::orderBy('id', 'desc')->get();
        return view('admin.role', $x);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => ['required'],
            'guard_name'    => ['required'],
            'permissions'   => ['required', 'array'],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        DB::beginTransaction();
        try {
            $role = Role::create([
                'name'          => $request->name,
                'guard_name'    => $request->guard_name,
            ]);
            $role->givePermissionTo($request->permissions);
            DB::commit();
            Alert::success('Aviso', 'Dato <b>' . $role->name . '</b> registrado exitosamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            DB::rollback();
            Alert::error('Aviso', 'Dato <b>' . $role->name . '</b> error al registralo : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function show(Request $request)
    {
        $role = Role::with('permissions')->find($request->id);
        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Dato de rol por id',
            'data'      => $role
        ], Response::HTTP_OK);
    }
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'          => ['required'],
            'guard_name'    => ['required'],
            'permissions'   => ['required', 'array'],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        DB::beginTransaction();
        try {
            $role = Role::find($request->id);
            $role->update([
                'name'          => $request->name,
                'guard_name'    => $request->guard_name,
            ]);
            $role->syncPermissions($request->permissions);
            DB::commit();
            Alert::success('Aviso', 'Dato <b>' . $role->name . '</b> actualizado exitosamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            DB::rollback();
            Alert::error('Aviso', 'Dato <b>' . $role->name . '</b> error al actualizarlo : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function destroy(Request $request)
    {
        try {
            $role = Role::find($request->id);
            $role->delete();
            Alert::success('Aviso', 'Dato <b>' . $role->name . '</b> eliminado exitosamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $role->name . '</b> error al eliminarlo : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }
}
