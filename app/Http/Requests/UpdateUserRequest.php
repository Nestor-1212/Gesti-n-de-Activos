<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('user'));
    }

    public function rules(): array
    {
        $id = $this->route('user')?->id;

        return [
            'name'     => ['required', 'string', 'max:100'],
            'email'    => ['required', 'email', 'max:150', "unique:users,email,{$id}"],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role'     => ['required', 'exists:roles,name'],
            'active'   => ['boolean'],
        ];
    }
}
