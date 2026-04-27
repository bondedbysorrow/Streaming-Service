<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductSearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'query' => 'required|string|min:2|max:100'
        ];
    }

    public function messages()
    {
        return [
            'query.required' => 'El término de búsqueda es requerido',
            'query.min' => 'El término debe tener al menos 2 caracteres',
            'query.max' => 'El término no puede exceder 100 caracteres'
        ];
    }
}
