<?php

declare(strict_types=1);

namespace App\Http\Requests\Cart;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    /**
     * Determinar si el usuario puede realizar la petición.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Reglas de validación.
     */
    public function rules(): array
    {
        return [

            'product_id' => [
                'required',
                'integer',
                'exists:products,id',
            ],

            'cantidad' => [
                'nullable',
                'integer',
                'min:1',
                'max:8',
            ],

        ];
    }

    /**
     * Mensajes personalizados.
     */
    public function messages(): array
    {
        return [

            'product_id.required' => 'Debe seleccionar un producto.',

            'product_id.exists' => 'El producto seleccionado no existe.',

            'cantidad.integer' => 'La cantidad debe ser un número entero.',

            'cantidad.min' => 'La cantidad mínima es 1.',

            'cantidad.max' => 'Solo puedes comprar hasta 8 unidades de este producto.',

        ];
    }
}