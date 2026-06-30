<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\HistorialPrecio;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductObserver
{
    /**
     * Antes de crear un producto.
     */
    public function creating(Product $product): void
    {
        // Generar slug automáticamente
        if (empty($product->slug)) {
            $product->slug = Str::slug($product->name);
        }
    }

    /**
     * Después de crear.
     */
    public function created(Product $product): void
    {
        //
    }

    /**
     * Antes de actualizar.
     */
    public function updating(Product $product): void
    {
        // Si cambia el nombre actualizar slug
        if ($product->isDirty('name')) {
            $product->slug = Str::slug($product->name);
        }
    }

    /**
     * Después de actualizar.
     */
    public function updated(Product $product): void
    {
        // Cambio del precio de costo
        if ($product->wasChanged('cost_price')) {

            HistorialPrecio::create([

                'product_id' => $product->id,

                'usuario_id' => Auth::id(),

                'tipo_precio' => 'COSTO',

                'precio_anterior' => $product->getOriginal('cost_price'),

                'precio_nuevo' => $product->cost_price,

                'motivo' => 'Actualización de precio de costo'

            ]);

        }

        // Cambio del precio de venta
        if ($product->wasChanged('sale_price')) {

            HistorialPrecio::create([

                'product_id' => $product->id,

                'usuario_id' => Auth::id(),

                'tipo_precio' => 'VENTA',

                'precio_anterior' => $product->getOriginal('sale_price'),

                'precio_nuevo' => $product->sale_price,

                'motivo' => 'Actualización de precio de venta'

            ]);

        }
    }

    /**
     * Antes de eliminar.
     */
    public function deleting(Product $product): void
    {
        //
    }

    /**
     * Después de eliminar.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Restaurado.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Eliminado permanentemente.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}