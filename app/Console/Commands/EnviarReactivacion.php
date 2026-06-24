<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Product;
use App\Notifications\UsuarioReactivacion;

class EnviarReactivacion extends Command
{
    protected $signature = 'app:enviar-reactivacion';

    protected $description = 'Enviar correos de reactivación a usuarios inactivos';

    public function handle()
    {
        $usuarios = User::where('updated_at', '<', now()->subDays(7))->get();

        foreach ($usuarios as $user) {

            $productos = Product::inRandomOrder()->take(3)->get();

            $user->notify(new UsuarioReactivacion($productos));
        }

        $this->info('Correos enviados correctamente');
    }
}