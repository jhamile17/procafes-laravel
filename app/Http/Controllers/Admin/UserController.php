<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $users = User::with('role')
            ->latest()
            ->paginate(10);

        return view(
            'admin.users.index',
            compact('users')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORMULARIO CREAR
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        $roles = Role::where('estado', true)
            ->orderBy('id')
            ->get();

        return view(
            'admin.users.create',
            compact('roles')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GUARDAR
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'email' => [
                    'required',
                    'email',
                    'max:255',
                    'unique:users,email',
                ],

                'password' => [
                    'required',
                    'string',
                    'min:6',
                    'confirmed',
                ],

                'phone' => [
                    'nullable',
                    'string',
                    'max:20',
                ],

                'document_type' => [
                    'required',
                    'in:dni,ce',
                ],

                'document_number' => [
                    'required',
                    'string',
                    'max:20',
                ],

                'address' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'role_id' => [
                    'required',
                    'exists:roles,id',
                ],
            ],
            [
                'name.required' =>
                    'El nombre es obligatorio.',

                'email.required' =>
                    'El correo electrónico es obligatorio.',

                'email.email' =>
                    'Ingresa un correo electrónico válido.',

                'email.unique' =>
                    'Este correo electrónico ya está registrado.',

                'password.required' =>
                    'La contraseña es obligatoria.',

                'password.min' =>
                    'La contraseña debe tener al menos 6 caracteres.',

                'password.confirmed' =>
                    'Las contraseñas no coinciden.',

                'document_type.required' =>
                    'Selecciona el tipo de documento.',

                'document_number.required' =>
                    'El número de documento es obligatorio.',

                'role_id.required' =>
                    'Selecciona un rol.',

                'role_id.exists' =>
                    'El rol seleccionado no es válido.',
            ]
        );


        /*
        |----------------------------------------------------------------------
        | VALIDACIÓN DEL DOCUMENTO
        |----------------------------------------------------------------------
        */

        if ($request->document_type === 'dni') {

            $request->validate(
                [
                    'document_number' => [
                        'required',
                        'digits:8',
                    ],
                ],
                [
                    'document_number.digits' =>
                        'El DNI debe tener 8 dígitos.',
                ]
            );

        } else {

            $request->validate(
                [
                    'document_number' => [
                        'required',
                        'min:9',
                        'max:12',
                    ],
                ],
                [
                    'document_number.min' =>
                        'El carnet de extranjería debe tener al menos 9 caracteres.',

                    'document_number.max' =>
                        'El carnet de extranjería no debe superar 12 caracteres.',
                ]
            );
        }


        /*
        |----------------------------------------------------------------------
        | CREAR USUARIO
        |----------------------------------------------------------------------
        */

        User::create([
            'name' => $request->name,

            'email' => $request->email,

            'password' => Hash::make(
                $request->password
            ),

            'phone' => $request->phone,

            'document_type' =>
                $request->document_type,

            'document_number' =>
                $request->document_number,

            'address' =>
                $request->address,

            'role_id' =>
                $request->role_id,
        ]);


        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'Usuario creado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FORMULARIO EDITAR
    |--------------------------------------------------------------------------
    */

    public function edit(User $user)
    {
        $roles = Role::where('estado', true)
            ->orderBy('id')
            ->get();

        return view(
            'admin.users.edit',
            compact(
                'user',
                'roles'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        User $user
    ) {

        $request->validate(
            [
                'name' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'email' => [
                    'required',
                    'email',
                    'max:255',
                    'unique:users,email,' . $user->id,
                ],

                'phone' => [
                    'nullable',
                    'string',
                    'max:20',
                ],

                'document_type' => [
                    'required',
                    'in:dni,ce',
                ],

                'document_number' => [
                    'required',
                    'string',
                    'max:20',
                ],

                'address' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'role_id' => [
                    'required',
                    'exists:roles,id',
                ],

                'password' => [
                    'nullable',
                    'string',
                    'min:6',
                    'confirmed',
                ],
            ],
            [
                'email.unique' =>
                    'Este correo electrónico ya está registrado.',

                'role_id.required' =>
                    'Selecciona un rol.',

                'role_id.exists' =>
                    'El rol seleccionado no es válido.',

                'password.min' =>
                    'La contraseña debe tener al menos 6 caracteres.',

                'password.confirmed' =>
                    'Las contraseñas no coinciden.',
            ]
        );


        /*
        |----------------------------------------------------------------------
        | VALIDACIÓN DEL DOCUMENTO
        |----------------------------------------------------------------------
        */

        if ($request->document_type === 'dni') {

            $request->validate(
                [
                    'document_number' => [
                        'required',
                        'digits:8',
                    ],
                ],
                [
                    'document_number.digits' =>
                        'El DNI debe tener 8 dígitos.',
                ]
            );

        } else {

            $request->validate(
                [
                    'document_number' => [
                        'required',
                        'min:9',
                        'max:12',
                    ],
                ],
                [
                    'document_number.min' =>
                        'El carnet de extranjería debe tener al menos 9 caracteres.',

                    'document_number.max' =>
                        'El carnet de extranjería no debe superar 12 caracteres.',
                ]
            );
        }


        /*
        |----------------------------------------------------------------------
        | DATOS A ACTUALIZAR
        |----------------------------------------------------------------------
        */

        $data = [
            'name' => $request->name,

            'email' => $request->email,

            'phone' => $request->phone,

            'document_type' =>
                $request->document_type,

            'document_number' =>
                $request->document_number,

            'address' =>
                $request->address,

            'role_id' =>
                $request->role_id,
        ];


        /*
        |----------------------------------------------------------------------
        | CONTRASEÑA
        |----------------------------------------------------------------------
        */

        if ($request->filled('password')) {

            $data['password'] = Hash::make(
                $request->password
            );
        }


        $user->update($data);


        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'Usuario actualizado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ELIMINAR
    |--------------------------------------------------------------------------
    */

    public function destroy(User $user)
    {
        /*
        |----------------------------------------------------------------------
        | No permitir eliminar administradores
        |----------------------------------------------------------------------
        */

        if ($user->role?->codigo === 'ADMIN') {

            return back()->with(
                'error',
                'No puedes eliminar administradores.'
            );
        }


        $user->delete();


        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'Usuario eliminado correctamente.'
            );
    }
}