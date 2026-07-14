<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class CheckEmailController extends Controller
{
    public function __invoke(): View
    {
        $email = session('registration_email');

        if (! $email) {
            abort(404);
        }

        return view('auth.check-email', [

            'email' => $email,

            'seconds' => 60,

            'canResend' => false,

        ]);
    }
}