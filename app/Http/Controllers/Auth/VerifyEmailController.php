<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\UserRegistrationService;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    public function __invoke(
        int $id,
        string $hash,
        UserRegistrationService $registrationService
    ): RedirectResponse {

        $user = User::findOrFail($id);

        if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'El enlace de verificación no es válido.');
        }

        $registrationService->verifyEmail($user);

        return redirect()
            ->route('login')
            ->with('verified', true);
    }
}