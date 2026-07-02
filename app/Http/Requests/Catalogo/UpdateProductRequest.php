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

            'categories_id' => [
                'required',
                'exists:categories,id',
            ],

            'brand_id' => [
                'nullable',
                'exists:brands,id',
            ],

            'tipo_consumo_id' => [
                'nullable',
                'exists:tipo_consumos,id',
            ],

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'nullable',
                Rule::unique('products', 'slug')->ignore($product->id),
            ],

            'sku' => [
                'nullable',
                Rule::unique('products', 'sku')->ignore($product->id),
            ],

            'barcode' => [
                'nullable',
                Rule::unique('products', 'barcode')->ignore($product->id),
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
        ];
    }

    public function messages(): array
    {
        return [

            'sale_price.gte' =>
                'El precio de venta debe ser mayor o igual al costo.',

            'categories_id.required' =>
                'La categoría es obligatoria.',

            'categories_id.exists' =>
                'La categoría seleccionada no existe.',

            'brand_id.exists' =>
                'La marca seleccionada no existe.',

            'tipo_consumo_id.exists' =>
                'El tipo de consumo seleccionado no existe.',
        ];
    }
}