<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\PendingRegistrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ResendRegistrationController extends Controller
{
    public function __invoke(
        Request $request,
        PendingRegistrationService $service
    ): RedirectResponse {

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $service->reenviar(
            $request->email
        );

        return back()->with(
            'success',
            'Se envió nuevamente el correo de verificación.'
        );
    }
}