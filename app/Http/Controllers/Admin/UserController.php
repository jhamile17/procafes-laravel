<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTADO
    |--------------------------------------------------------------------------
    */

    public function index(): View
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

    public function create(): View
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

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate(
            [
                'nombres' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'apellido_paterno' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'apellido_materno' => [
                    'nullable',
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

                'celular' => [
                    'nullable',
                    'string',
                    'max:20',
                ],

                'tipo_documento' => [
                    'required',
                    'in:dni,ce',
                ],

                'numero_documento' => [
                    'required',
                    'string',
                    'max:20',
                ],

                'direccion' => [
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
                'nombres.required' =>
                    'Los nombres son obligatorios.',

                'apellido_paterno.required' =>
                    'El apellido paterno es obligatorio.',

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

                'tipo_documento.required' =>
                    'Selecciona el tipo de documento.',

                'numero_documento.required' =>
                    'El número de documento es obligatorio.',

                'role_id.required' =>
                    'Selecciona un rol.',

                'role_id.exists' =>
                    'El rol seleccionado no es válido.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDACIÓN DEL DOCUMENTO
        |--------------------------------------------------------------------------
        */

        if ($request->tipo_documento === 'dni') {

            $request->validate(
                [
                    'numero_documento' => [
                        'required',
                        'digits:8',
                    ],
                ],
                [
                    'numero_documento.digits' =>
                        'El DNI debe tener 8 dígitos.',
                ]
            );

        } else {

            $request->validate(
                [
                    'numero_documento' => [
                        'required',
                        'min:9',
                        'max:12',
                    ],
                ],
                [
                    'numero_documento.min' =>
                        'El carnet de extranjería debe tener al menos 9 caracteres.',

                    'numero_documento.max' =>
                        'El carnet de extranjería no debe superar 12 caracteres.',
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NOMBRE COMPLETO
        |--------------------------------------------------------------------------
        */

        $nombreCompleto = User::construirNombreCompleto(
            $validated['nombres'],
            $validated['apellido_paterno'],
            $validated['apellido_materno'] ?? null
        );


        /*
        |--------------------------------------------------------------------------
        | CREAR USUARIO
        |--------------------------------------------------------------------------
        */

        User::create([

            'role_id' =>
                $validated['role_id'],

            'name' =>
                $nombreCompleto,

            'nombres' =>
                $validated['nombres'],

            'apellido_paterno' =>
                $validated['apellido_paterno'],

            'apellido_materno' =>
                $validated['apellido_materno'] ?? null,

            'tipo_documento' =>
                $validated['tipo_documento'],

            'numero_documento' =>
                $validated['numero_documento'],

            'email' =>
                strtolower(trim($validated['email'])),

            'password' =>
                Hash::make($validated['password']),

            'celular' =>
                $validated['celular'] ?? null,

            'direccion' =>
                $validated['direccion'] ?? null,

            'provider' =>
                User::PROVIDER_LOCAL,

            'has_local_password' =>
                true,

            'estado' =>
                true,
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

    public function edit(User $user): View
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
    ): RedirectResponse {

        $validated = $request->validate(
            [
                'nombres' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'apellido_paterno' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'apellido_materno' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'email' => [
                    'required',
                    'email',
                    'max:255',
                    'unique:users,email,' . $user->id,
                ],

                'celular' => [
                    'nullable',
                    'string',
                    'max:20',
                ],

                'tipo_documento' => [
                    'required',
                    'in:dni,ce',
                ],

                'numero_documento' => [
                    'required',
                    'string',
                    'max:20',
                ],

                'direccion' => [
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
                'nombres.required' =>
                    'Los nombres son obligatorios.',

                'apellido_paterno.required' =>
                    'El apellido paterno es obligatorio.',

                'email.unique' =>
                    'Este correo electrónico ya está registrado.',

                'tipo_documento.required' =>
                    'Selecciona el tipo de documento.',

                'numero_documento.required' =>
                    'El número de documento es obligatorio.',

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
        |--------------------------------------------------------------------------
        | VALIDACIÓN DEL DOCUMENTO
        |--------------------------------------------------------------------------
        */

        if ($request->tipo_documento === 'dni') {

            $request->validate(
                [
                    'numero_documento' => [
                        'required',
                        'digits:8',
                    ],
                ],
                [
                    'numero_documento.digits' =>
                        'El DNI debe tener 8 dígitos.',
                ]
            );

        } else {

            $request->validate(
                [
                    'numero_documento' => [
                        'required',
                        'min:9',
                        'max:12',
                    ],
                ],
                [
                    'numero_documento.min' =>
                        'El carnet de extranjería debe tener al menos 9 caracteres.',

                    'numero_documento.max' =>
                        'El carnet de extranjería no debe superar 12 caracteres.',
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NOMBRE COMPLETO
        |--------------------------------------------------------------------------
        */

        $nombreCompleto = User::construirNombreCompleto(
            $validated['nombres'],
            $validated['apellido_paterno'],
            $validated['apellido_materno'] ?? null
        );


        /*
        |--------------------------------------------------------------------------
        | DATOS A ACTUALIZAR
        |--------------------------------------------------------------------------
        */

        $data = [

            'name' =>
                $nombreCompleto,

            'nombres' =>
                $validated['nombres'],

            'apellido_paterno' =>
                $validated['apellido_paterno'],

            'apellido_materno' =>
                $validated['apellido_materno'] ?? null,

            'email' =>
                strtolower(trim($validated['email'])),

            'celular' =>
                $validated['celular'] ?? null,

            'tipo_documento' =>
                $validated['tipo_documento'],

            'numero_documento' =>
                $validated['numero_documento'],

            'direccion' =>
                $validated['direccion'] ?? null,

            'role_id' =>
                $validated['role_id'],
        ];


        /*
        |--------------------------------------------------------------------------
        | CONTRASEÑA
        |--------------------------------------------------------------------------
        */

        if ($request->filled('password')) {

            $data['password'] =
                Hash::make($request->password);

            $data['has_local_password'] = true;
        }


        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR
        |--------------------------------------------------------------------------
        */

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
    | ACTIVAR / DESACTIVAR USUARIO
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(User $user): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | NO PERMITIR DESACTIVARSE A SÍ MISMO
        |--------------------------------------------------------------------------
        */

        if (auth()->id() === $user->id) {

            return back()->with(
                'error',
                'No puedes desactivar tu propia cuenta.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NO PERMITIR DESACTIVAR ADMINISTRADORES
        |--------------------------------------------------------------------------
        */

        if ($user->role?->codigo === 'ADMIN') {

            return back()->with(
                'error',
                'No puedes desactivar a un administrador.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CAMBIAR ESTADO
        |--------------------------------------------------------------------------
        */

        $user->estado = ! $user->estado;

        $user->save();


        /*
        |--------------------------------------------------------------------------
        | MENSAJE
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                $user->estado
                    ? 'Usuario activado correctamente.'
                    : 'Usuario desactivado correctamente.'
            );
    }
}