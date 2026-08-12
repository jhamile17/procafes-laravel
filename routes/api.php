<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AlertController;
use App\Http\Controllers\Api\NotificacionController;
use App\Http\Controllers\Api\DeviceTokenApiController;


/*
|--------------------------------------------------------------------------
| API - PROCÁFES ADMIN MÓVIL
|--------------------------------------------------------------------------
*/


/*
|--------------------------------------------------------------------------
| AUTENTICACIÓN
|--------------------------------------------------------------------------
*/

// Login
Route::post('/login', [AuthController::class, 'login']);


/*
|--------------------------------------------------------------------------
| RUTAS PROTEGIDAS - ADMINISTRADOR
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | AUTENTICACIÓN
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout']);


    /*
    |--------------------------------------------------------------------------
    | PRODUCTOS
    |--------------------------------------------------------------------------
    */

    Route::prefix('products')->group(function () {

        // Listado de productos
        Route::get('/', [ProductController::class, 'index']);

        // Detalle de producto
        Route::get('/{id}', [ProductController::class, 'show'])
            ->where('id', '[0-9]+');

        // Actualizar stock
        Route::post('/update-stock', [ProductController::class, 'updateStock']);
    });


    /*
    |--------------------------------------------------------------------------
    | ALERTAS
    |--------------------------------------------------------------------------
    */

    // Alertas actuales
    Route::get('/alertas', [ProductController::class, 'alertasActuales']);

    // Historial de alertas
    Route::get('/alertas/historial', [AlertController::class, 'index']);


    /*
    |--------------------------------------------------------------------------
    | NOTIFICACIONES
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/notificaciones/enviar',
        [NotificacionController::class, 'enviar']
    );


    /*
    |--------------------------------------------------------------------------
    | DEVICE TOKENS
    |--------------------------------------------------------------------------
    */

    Route::post(
        '/device/register',
        [DeviceTokenApiController::class, 'register']
    );

    Route::get(
        '/device/user/{userId}',
        [DeviceTokenApiController::class, 'getTokensByUser']
    );

    Route::delete(
        '/device/delete',
        [DeviceTokenApiController::class, 'deleteToken']
    );
});