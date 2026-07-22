<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\ConfiguracionEmpresa;

class UbicanosController extends Controller
{
    /**
     * Mostrar la página Ubícanos.
     */
    public function index()
    {
        $empresa = ConfiguracionEmpresa::obtener();

        return view('ubicanos', compact('empresa'));
    }
}