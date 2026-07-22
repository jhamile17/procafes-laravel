<?php

namespace App\Services\Home;

class MethodService
{
    public function obtenerTodos(): array
    {
        return [

            [
                'image' => 'v60hario.png',
                'name' => 'V60 Hario',
                'description' => 'Filtrado limpio con notas suaves y aromáticas.',
            ],

            [
                'image' => 'chemex.png',
                'name' => 'Chemex',
                'description' => 'Preparación elegante con gran equilibrio.',
            ],

            [
                'image' => 'aeropress.png',
                'name' => 'AeroPress',
                'description' => 'Extracción rápida con mucho cuerpo.',
            ],

            [
                'image' => 'prensa.png',
                'name' => 'Prensa Francesa',
                'description' => 'Ideal para obtener un café intenso.',
            ],

            [
                'image' => 'moka.png',
                'name' => 'Moka Italiana',
                'description' => 'El clásico espresso italiano.',
            ],

            [
                'image' => 'syphon.png',
                'name' => 'Syphon',
                'description' => 'Método que resalta aromas complejos.',
            ],

        ];
    }
}