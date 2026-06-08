<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMaintenanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->hasRole(['super-admin', 'admin', 'supervisor']);
    }

    public function rules(): array
    {
        return [
            'type'             => ['required', 'in:preventive,corrective,upgrade'],
            'description'      => ['required', 'string', 'max:2000'],
            'maintenance_date' => ['required', 'date'],
            'completed_date'   => ['nullable', 'date', 'after_or_equal:maintenance_date'],
            'technician'       => ['nullable', 'string', 'max:150'],
            'cost'             => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'status'           => ['required', 'in:pending,in_progress,completed'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ];
    }
}
