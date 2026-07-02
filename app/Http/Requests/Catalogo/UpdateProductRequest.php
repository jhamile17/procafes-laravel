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
                Rule::unique('products', 'slug')->ignore($product),
            ],

            'sku' => [
                'nullable',
                Rule::unique('products', 'sku')->ignore($product),
            ],

            'barcode' => [
                'nullable',
                Rule::unique('products', 'barcode')->ignore($product),
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
}