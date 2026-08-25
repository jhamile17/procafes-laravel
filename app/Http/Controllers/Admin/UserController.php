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

    public function index(Request $request): View
    {
        $query = User::with('role');

        /*
        |--------------------------------------------------------------------------
        | BÚSQUEDA
        |--------------------------------------------------------------------------
        */

        if ($request->filled('buscar')) {

            $buscar = trim($request->buscar);

            $query->where(function ($q) use ($buscar) {

                $q->where('name', 'like', "%{$buscar}%")
                    ->orWhere('nombres', 'like', "%{$buscar}%")
                    ->orWhere('apellido_paterno', 'like', "%{$buscar}%")
                    ->orWhere('apellido_materno', 'like', "%{$buscar}%")
                    ->orWhere('email', 'like', "%{$buscar}%")
                    ->orWhere('numero_documento', 'like', "%{$buscar}%")
                    ->orWhere('celular', 'like', "%{$buscar}%");

            });
        }


        /*
        |--------------------------------------------------------------------------
        | FILTRO POR ROL
        |--------------------------------------------------------------------------
        */

        if ($request->filled('rol')) {

            $query->where('role_id', $request->rol);

        }


        /*
        |--------------------------------------------------------------------------
        | FILTRO POR ESTADO
        |--------------------------------------------------------------------------
        */

        if ($request->filled('estado')) {

            $query->where(
                'estado',
                $request->estado
            );

        }


        /*
        |--------------------------------------------------------------------------
        | FILTRO POR VERIFICACIÓN
        |--------------------------------------------------------------------------
        */

        if ($request->filled('verificado')) {

            if ($request->verificado === '1') {

                $query->whereNotNull('email_verified_at');

            } elseif ($request->verificado === '0') {

                $query->whereNull('email_verified_at');

            }

        }


        /*
        |--------------------------------------------------------------------------
        | USUARIOS
        |--------------------------------------------------------------------------
        */

        $users = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | ROLES
        |--------------------------------------------------------------------------
        */

        $roles = Role::where('estado', true)
            ->orderBy('id')
            ->get();


        return view(
            'admin.users.index',
            compact(
                'users',
                'roles'
            )
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
                    'unique:users,numero_documento',
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

                'numero_documento.unique' =>
                    'Este número de documento ya está registrado.',

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
    |
    | Se mantiene por Route::resource().
    | Actualmente el listado utiliza modal.
    |
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
    | ACTUALIZAR ROL / PERMISOS
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        User $user
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | SOLO ADMINISTRADOR PRINCIPAL
        |--------------------------------------------------------------------------
        */

        if (! auth()->user()->isAdminPrincipal()) {

            return redirect()
                ->route('admin.users.index')
                ->with(
                    'error',
                    'No tienes permisos para modificar los roles de los usuarios.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | NO PERMITIR MODIFICAR EL PROPIO ROL
        |--------------------------------------------------------------------------
        */

        if (auth()->id() === $user->id) {

            return redirect()
                ->route('admin.users.index')
                ->with(
                    'error',
                    'No puedes modificar tu propio rol.'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDAR ROL
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate(
            [
                'role_id' => [
                    'required',
                    'exists:roles,id',
                ],
            ],
            [
                'role_id.required' =>
                    'Debes seleccionar un rol.',

                'role_id.exists' =>
                    'El rol seleccionado no es válido.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | ACTUALIZAR ROL
        |--------------------------------------------------------------------------
        */

        $user->update([
            'role_id' => $validated['role_id'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | REDIRECCIÓN
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'Rol del usuario actualizado correctamente.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | ACTIVAR / DESACTIVAR USUARIO
    |--------------------------------------------------------------------------
    */

    public function toggleStatus(
        User $user
    ): RedirectResponse {

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
        |
        | Para cambiar los permisos de un administrador:
        |
        | ADMIN → CLIENTE
        |
        | debe utilizarse el modal de roles.
        |
        */

        if ($user->isAdmin()) {

            return back()->with(
                'error',
                'No puedes activar o desactivar una cuenta de administrador desde esta opción.'
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