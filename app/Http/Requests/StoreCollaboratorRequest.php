<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCollaboratorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Collaborator::class);
    }

    public function rules(): array
    {
        return [
            'first_name'    => ['required', 'string', 'max:100'],
            'last_name'     => ['required', 'string', 'max:100'],
            'cedula'        => ['required', 'string', 'max:20', 'unique:collaborators,cedula'],
            'department_id' => ['nullable', 'exists:departments,id'],
            'position'      => ['nullable', 'string', 'max:100'],
            'email'         => ['nullable', 'email', 'max:150', 'unique:collaborators,email'],
            'phone'         => ['nullable', 'string', 'max:20'],
            'supervisor'    => ['nullable', 'string', 'max:150'],
            'status'        => ['required', 'in:active,inactive'],
            'photo'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'cedula.unique' => 'Ya existe un colaborador con esta cédula.',
            'email.unique'  => 'Este correo ya está registrado en otro colaborador.',
        ];
    }
}
