<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMaintenanceRequest extends FormRequest
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
            'equipment_id'     => ['required', 'exists:equipment,id'],
            'type'             => ['required', 'in:preventive,corrective,upgrade'],
            'description'      => ['required', 'string', 'max:2000'],
            'maintenance_date' => ['required', 'date', 'before_or_equal:today'],
            'technician'       => ['nullable', 'string', 'max:150'],
            'cost'             => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'status'           => ['required', 'in:pending,in_progress,completed'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ];
    }
}
