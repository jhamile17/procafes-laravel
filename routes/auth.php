<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Auth\Register;
use App\Livewire\Pages\Auth\ForgotPassword;
use App\Livewire\Pages\Auth\ResetPassword;

Route::middleware('guest')->group(function () {

    Route::get('login', Login::class)
        ->name('login');
    Route::get('register', Register::class)
        ->name('register');
    Route::get('forgot-password', ForgotPassword::class)
        ->name('password.request');
    Route::get('reset-password/{token}', ResetPassword::class)
        ->name('password.reset');
});