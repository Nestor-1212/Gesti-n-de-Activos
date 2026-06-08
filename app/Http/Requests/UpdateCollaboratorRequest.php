<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCollaboratorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('collaborator'));
    }

    public function rules(): array
    {
        $id = $this->route('collaborator')?->id;

        return [
            'first_name'    => ['required', 'string', 'max:100'],
            'last_name'     => ['required', 'string', 'max:100'],
            'cedula'        => ['required', 'string', 'max:20', "unique:collaborators,cedula,{$id}"],
            'department_id' => ['nullable', 'exists:departments,id'],
            'position'      => ['nullable', 'string', 'max:100'],
            'email'         => ['nullable', 'email', 'max:150', "unique:collaborators,email,{$id}"],
            'phone'         => ['nullable', 'string', 'max:20'],
            'supervisor'    => ['nullable', 'string', 'max:150'],
            'status'        => ['required', 'in:active,inactive'],
            'photo'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
