<?php

namespace App\Http\Requests\Catalogo;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
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
                'string',
                'max:255',
                'unique:products,slug',
            ],

            /*
            |--------------------------------------------------------------------------
            | SKU
            |--------------------------------------------------------------------------
            */

            'sku' => [
                'nullable',
                'string',
                'max:50',
                'unique:products,sku',
            ],

            /*
            |--------------------------------------------------------------------------
            | CÓDIGO DE BARRAS
            |--------------------------------------------------------------------------
            */

            'barcode' => [
                'nullable',
                'string',
                'max:100',
                'unique:products,barcode',
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
            | ESTADO
            |--------------------------------------------------------------------------
            |
            | No es obligatorio.
            | ProductService lo determina según el stock.
            |
            */

            'status' => [
                'nullable',
                'boolean',
            ],

            /*
            |--------------------------------------------------------------------------
            | IMAGEN
            |--------------------------------------------------------------------------
            */

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | PRECIO
            |--------------------------------------------------------------------------
            */

            'sale_price.required' =>
                'El precio de venta es obligatorio.',

            'sale_price.numeric' =>
                'El precio de venta debe ser un número.',

            'sale_price.min' =>
                'El precio de venta no puede ser negativo.',

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
                'La imagen no debe superar los 2 MB.',
        ];
    }
}