<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\UpdatePerfilRequest;
use App\Http\Requests\Customer\UpdatePhotoRequest;
use App\Http\Requests\Customer\UpdatePasswordRequest;
use App\Services\Cliente\PerfilService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
class PerfilController extends Controller
{
    public function __construct(
        private readonly PerfilService $perfilService
    ) {}

    /**
     * Mostrar perfil del cliente.
     */
    public function index(Request $request)
    {
        $data = $this->perfilService->getProfileData(
            $request->user()
        );

        return view('customer.perfil', $data);
    }

    /**
     * Mostrar formulario de edición.
     */
    public function edit(Request $request)
    {
        $data = $this->perfilService->getProfileData(
            $request->user()
        );

        return view('customer.perfil-edit', $data);
    }

    /**
     * Actualizar información personal.
     */
   public function update(UpdatePerfilRequest $request): RedirectResponse
    {
        $this->perfilService->updateProfile(
            $request->user(),
            $request->validated()
        );

        return back()->with(
            'success',
            'Perfil actualizado correctamente.'
        );
    }

    /**
     * Actualizar foto de perfil.
     */
    public function updatePhoto(UpdatePhotoRequest $request)
    {
        $this->perfilService->updatePhoto(
            $request->user(),
            $request->file('foto_perfil')
        );

        return back()->with(
            'success',
            'Foto de perfil actualizada correctamente.'
        );
    }

    /**
     * Actualizar contraseña.
     */
    /**
 * Actualizar contraseña.
 */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $this->perfilService->updatePassword(
            $request->user(),
            $request->validated()
        );

        return redirect()
            ->route('customer.profile.settings')
            ->with(
                'success',
                'Contraseña actualizada correctamente.'
            );
    }
    /**
     * Mostrar configuración de la cuenta.
     */
    public function settings(Request $request)
    {
        $data = $this->perfilService->getProfileData(
            $request->user()
        );

        return view(
            'customer.configuracion',
            $data
        );
    }
}