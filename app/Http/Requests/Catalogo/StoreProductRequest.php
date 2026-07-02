<?php

namespace App\Http\Requests\Catalogo;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'categories_id' => [
                'nullable',
                'exists:categories,id',
            ],

            'brand_id' => [
                'nullable',
                'exists:brands,id',
            ],

            'tipo_consumo_id' => [
                'nullable',
                'exists:tipos_consumo,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'unique:products,slug',
            ],

            'sku' => [
                'nullable',
                'string',
                'max:50',
                'unique:products,sku',
            ],

            'barcode' => [
                'nullable',
                'string',
                'max:100',
                'unique:products,barcode',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'cost_price' => [
                'required',
                'numeric',
                'min:0',
            ],

            'sale_price' => [
                'required',
                'numeric',
                'gte:cost_price',
            ],

            'stock' => [
                'required',
                'integer',
                'min:0',
            ],

            'stock_minimo' => [
                'required',
                'integer',
                'min:0',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'status' => [
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [

            'sale_price.gte' =>
                'El precio de venta debe ser mayor o igual al precio de compra.',

            'categories_id.exists' =>
                'La categoría seleccionada no existe.',

            'brand_id.exists' =>
                'La marca seleccionada no existe.',

            'tipo_consumo_id.exists' =>
                'El tipo de consumo seleccionado no existe.',
        ];
    }
}