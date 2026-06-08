<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
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
        $id = $this->route('department')?->id;

        return [
            'name'        => ['required', 'string', 'max:100', "unique:departments,name,{$id}"],
            'code'        => ['nullable', 'string', 'max:20', 'alpha_dash', "unique:departments,code,{$id}"],
            'description' => ['nullable', 'string', 'max:500'],
            'active'      => ['boolean'],
        ];
    }
}
