<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAssignmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Assignment::class);
    }

    public function rules(): array
    {
        return [
            'equipment_id'    => ['required', 'exists:equipment,id'],
            'collaborator_id' => ['required', 'exists:collaborators,id'],
            'assigned_at'     => ['required', 'date', 'before_or_equal:today'],
            'expected_return' => ['nullable', 'date', 'after:assigned_at'],
            'reason'          => ['nullable', 'string', 'max:255'],
            'observations'    => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'expected_return.after' => 'La fecha de devolución debe ser posterior a la fecha de entrega.',
        ];
    }
}
