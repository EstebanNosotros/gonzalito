<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use RealRashid\SweetAlert\Facades\Alert;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        $x['title']     = 'Usuarios';
        $x['data']      = User::get();
        $x['role']      = Role::get();
        return view('admin.user', $x);
    }

    public function profile()
    {
        $x['title']     = 'Perfil';
        $x['data']      = User::find(auth()->user()->id);
        //$x['role']      = Role::get();
        return view('admin.profile', $x);
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
        DB::beginTransaction();
        try {
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => bcrypt($request->password)
            ]);
            $user->assignRole($request->role);
            DB::commit();
            Alert::success('Aviso', 'Dato <b>' . $user->name . '</b> registrado exitosamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            DB::rollback();
            Alert::error('Aviso', 'Dato <b>' . $user->name . '</b> error al registralo : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function show(Request $request)
    {
        $user = UserResource::collection(User::where(['id' => $request->id])->get());
        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Dato de usuario por id',
            'data'      => $user[0]
        ], Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        $rules = [
            'name'      => ['required', 'string', 'max:255'],
            'password'  => ['nullable', 'string'],
            'role'      => ['required']
        ];

        if ($request->email != $request->old_email) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users'];
            $validator = Validator::make($request->all(), $rules);
        } else {
            $rules['email'] = ['required', 'string', 'email', 'max:255'];
            $validator = Validator::make($request->all(), $rules);
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)
                ->withInput();
        }
        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
        ];
        if (!empty($request->password)) {
            $data['password']   = bcrypt($request->password);
        }

        DB::beginTransaction();
        try {
            $user = User::find($request->id);
            $user->update($data);
            $user->syncRoles($request->role);
            DB::commit();
            Alert::success('Aviso', 'Dato <b>' . $user->name . '</b> actualizado exitosamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            DB::rollback();
            Alert::error('Aviso', 'Dato <b>' . $user->name . '</b> error al actualizarlo : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }

    public function destroy(Request $request)
    {
        try {
            $user = User::find($request->id);
            $user->delete();
            Alert::success('Aviso', 'Dato <b>' . $user->name . '</b> eliminado exitosamente')->toToast()->toHtml();
        } catch (\Throwable $th) {
            Alert::error('Aviso', 'Dato <b>' . $user->name . '</b> error al eliminarlo : ' . $th->getMessage())->toToast()->toHtml();
        }
        return back();
    }
}
