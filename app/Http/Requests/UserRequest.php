<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
    public function rules(): array
    {
        return [
           'name' => 'required|min:5|max:255',
           'lastname' => 'min:5|max:255',
           'dni' => 'unique:users|min:5|max:255',
           'email' => 'unique:users|min:5|max:255', 
           'phone' => 'min:5|max:255',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nom és obligatori',
            'name.min' => 'El nom ha de tenir al menys 5 caràcters',
            'name.max' => 'El nom no puede tener más de 255 caracteres',
            'lastname.min' => 'El llinatge ha de tener al menos 5 caracteres',
            'lastname.max' => 'El llinatge no puede tener más de 255 caracteres',
            'dni.unique' => 'El dni ya existe en la base de datos',
            'dni.min' => 'El dni debe tener al menos 5 caracteres',
            'dni.max' => 'El dni no puede tener más de 255 caracteres',
            'email.unique' => 'El email ya existe en la base de datos',
            'email.min' => 'El email debe tener al menos 5 caracteres',
            'email.max' => 'El email no puede tener más de 255 caracteres',
            'phone.min' => 'El teléfono debe tener al menos 5 caracteres',
            'phone.max' => 'El teléfono no puede tener más de 255 caracteres',
        ];
    }
}
