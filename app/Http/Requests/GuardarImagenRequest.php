<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuardarImagenRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */

    // Reglas de validación
    public function rules(): array
    {
        return [
            'url' => 'required|mimes:jpeg,jpg,bmp,png|max:10240',
        ];
    }

    // Mensajes personalizados de error
    public function messages() { 
        return [
            'url.required' => 'La imagen debe estar informada',
            'url.mimes' => 'La imagen debe ser de tipo jpeg, jpg, bmp o png',
            'url.max' => 'La imagen debe tener un tamaño máximo de 10MB',
        ]; 
    }
}
