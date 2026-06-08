<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreDepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole(['super-admin', 'admin']);
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:100', 'unique:departments,name'],
            'code'        => ['nullable', 'string', 'max:20', 'alpha_dash', 'unique:departments,code'],
            'description' => ['nullable', 'string', 'max:500'],
            'active'      => ['boolean'],
        ];
    }
}
