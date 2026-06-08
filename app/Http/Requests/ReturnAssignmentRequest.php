<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReturnAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('return', $this->route('assignment'));
    }

    public function rules(): array
    {
        return [
            'return_observations' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
