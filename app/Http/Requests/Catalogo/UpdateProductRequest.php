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
                'regex:/^[\pL\pN\s\.,"\-()]+$/u',
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
                'string',
                'max:50',
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
                'string',
                'max:100',
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
                'min:10',
                'max:1000',

                /*
                | Permite:
                | - letras
                | - números
                | - espacios
                | - punto
                | - coma
                | - comillas dobles
                | - guion
                | - paréntesis
                |
                | También permite tildes y ñ.
                */

                'regex:/^[\pL\pN\s\.,"\-()]+$/u',
            ],

            /*
            |--------------------------------------------------------------------------
            | PRECIO DE COSTO
            |--------------------------------------------------------------------------
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
                'gt:0',
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
            | NOMBRE
            |--------------------------------------------------------------------------
            */

            'name.required' =>
                'El nombre del producto es obligatorio.',

            'name.regex' =>
                'El nombre del producto contiene caracteres no permitidos.',

            /*
            |--------------------------------------------------------------------------
            | DESCRIPCIÓN
            |--------------------------------------------------------------------------
            */

            'description.min' =>
                'La descripción debe tener al menos 10 caracteres.',

            'description.max' =>
                'La descripción no puede superar los 1000 caracteres.',

            'description.regex' =>
                'La descripción solo puede contener letras, números, espacios, puntos, comas, comillas, guiones y paréntesis.',

            /*
            |--------------------------------------------------------------------------
            | PRECIO DE COSTO
            |--------------------------------------------------------------------------
            */

            'cost_price.numeric' =>
                'El precio de costo debe ser numérico.',

            'cost_price.min' =>
                'El precio de costo no puede ser negativo.',

            /*
            |--------------------------------------------------------------------------
            | PRECIO DE VENTA
            |--------------------------------------------------------------------------
            */

            'sale_price.required' =>
                'El precio de venta es obligatorio.',

            'sale_price.numeric' =>
                'El precio de venta debe ser numérico.',

            'sale_price.gt' =>
                'El precio de venta debe ser mayor a 0.',
                
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