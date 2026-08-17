<?php

namespace App\Http\Requests\Catalogo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $product = $this->route('product');

        return [

            /*
            |--------------------------------------------------------------------------
            | CATEGORÍA
            |--------------------------------------------------------------------------
            */

            'categories_id' => [
                'required',
                'exists:categories,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | MARCA
            |--------------------------------------------------------------------------
            */

            'brand_id' => [
                'nullable',
                'exists:brands,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | TIPO DE CONSUMO
            |--------------------------------------------------------------------------
            */

            'tipo_consumo_id' => [
                'nullable',
                'exists:tipos_consumo,id',
            ],

            /*
            |--------------------------------------------------------------------------
            | NOMBRE
            |--------------------------------------------------------------------------
            */

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            /*
            |--------------------------------------------------------------------------
            | SLUG
            |--------------------------------------------------------------------------
            */

            'slug' => [
                'nullable',
                Rule::unique('products', 'slug')
                    ->ignore($product->id),
            ],

            /*
            |--------------------------------------------------------------------------
            | SKU
            |--------------------------------------------------------------------------
            */

            'sku' => [
                'nullable',
                Rule::unique('products', 'sku')
                    ->ignore($product->id),
            ],

            /*
            |--------------------------------------------------------------------------
            | CÓDIGO DE BARRAS
            |--------------------------------------------------------------------------
            */

            'barcode' => [
                'nullable',
                Rule::unique('products', 'barcode')
                    ->ignore($product->id),
            ],

            /*
            |--------------------------------------------------------------------------
            | DESCRIPCIÓN
            |--------------------------------------------------------------------------
            */

            'description' => [
                'nullable',
                'string',
            ],

            /*
            |--------------------------------------------------------------------------
            | PRECIO DE COSTO
            |--------------------------------------------------------------------------
            |
            | No lo exigimos porque tu formulario de edición
            | actualmente no muestra este campo.
            |
            | Si no se envía, se conserva el valor existente.
            |
            */

            'cost_price' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | PRECIO DE VENTA
            |--------------------------------------------------------------------------
            */

            'sale_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | STOCK
            |--------------------------------------------------------------------------
            */

            'stock' => [
                'required',
                'integer',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | STOCK MÍNIMO
            |--------------------------------------------------------------------------
            */

            'stock_minimo' => [
                'required',
                'integer',
                'min:0',
            ],

            /*
            |--------------------------------------------------------------------------
            | IMAGEN
            |--------------------------------------------------------------------------
            |
            | La imagen es opcional al editar.
            |
            | Si no se selecciona:
            |     se conserva la imagen actual.
            |
            | Si se selecciona:
            |     se reemplaza por la nueva.
            |
            | Máximo: 5 MB.
            |
            */

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | CATEGORÍA
            |--------------------------------------------------------------------------
            */

            'categories_id.required' =>
                'La categoría es obligatoria.',

            'categories_id.exists' =>
                'La categoría seleccionada no existe.',

            /*
            |--------------------------------------------------------------------------
            | MARCA
            |--------------------------------------------------------------------------
            */

            'brand_id.exists' =>
                'La marca seleccionada no existe.',

            /*
            |--------------------------------------------------------------------------
            | TIPO DE CONSUMO
            |--------------------------------------------------------------------------
            */

            'tipo_consumo_id.exists' =>
                'El tipo de consumo seleccionado no existe.',

            /*
            |--------------------------------------------------------------------------
            | PRECIO
            |--------------------------------------------------------------------------
            */

            'sale_price.required' =>
                'El precio de venta es obligatorio.',

            'sale_price.numeric' =>
                'El precio de venta debe ser numérico.',

            'sale_price.min' =>
                'El precio de venta no puede ser negativo.',

            /*
            |--------------------------------------------------------------------------
            | STOCK
            |--------------------------------------------------------------------------
            */

            'stock.required' =>
                'El stock es obligatorio.',

            'stock.integer' =>
                'El stock debe ser un número entero.',

            'stock.min' =>
                'El stock no puede ser negativo.',

            /*
            |--------------------------------------------------------------------------
            | STOCK MÍNIMO
            |--------------------------------------------------------------------------
            */

            'stock_minimo.required' =>
                'El stock mínimo es obligatorio.',

            'stock_minimo.integer' =>
                'El stock mínimo debe ser un número entero.',

            'stock_minimo.min' =>
                'El stock mínimo no puede ser negativo.',

            /*
            |--------------------------------------------------------------------------
            | IMAGEN
            |--------------------------------------------------------------------------
            */

            'image.image' =>
                'El archivo seleccionado debe ser una imagen válida.',

            'image.mimes' =>
                'La imagen debe estar en formato JPG, JPEG, PNG o WEBP.',

            'image.max' =>
                'La imagen no debe superar los 5 MB.',
        ];
    }
}