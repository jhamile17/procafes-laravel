<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class WelcomeController extends Controller
{
    public function __invoke(): View
    {
        return view('auth.welcome', [
            'user' => auth()->user(),
        ]);
    }
}