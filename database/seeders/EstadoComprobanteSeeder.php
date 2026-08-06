<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\EstadoComprobante;
use Illuminate\Database\Seeder;

class EstadoComprobanteSeeder extends Seeder
{
    /**
     * Ejecutar Seeder.
     */
    public function run(): void
    {
        $estados = [

            [
                'codigo' => EstadoComprobante::PENDIENTE,
                'nombre' => 'Pendiente',
                'descripcion' => 'Comprobante pendiente de emisión.',
            ],

            [
                'codigo' => EstadoComprobante::EMITIDO,
                'nombre' => 'Emitido',
                'descripcion' => 'Comprobante emitido correctamente.',
            ],

            [
                'codigo' => EstadoComprobante::ANULADO,
                'nombre' => 'Anulado',
                'descripcion' => 'Comprobante anulado.',
            ],

        ];

        foreach ($estados as $estado) {

            EstadoComprobante::updateOrCreate(

                [
                    'codigo' => $estado['codigo'],
                ],

                [
                    'nombre' => $estado['nombre'],
                    'descripcion' => $estado['descripcion'],
                    'estado' => true,
                ]

            );

        }
    }
}