<?php

namespace App\Http\Requests;

use App\Enums\EquipmentStatus;
use App\Enums\EquipmentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateEquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('equipment'));
    }

    public function rules(): array
    {
        $id = $this->route('equipment')?->id;

        return [
            'brand'            => ['required', 'string', 'max:100'],
            'model'            => ['required', 'string', 'max:100'],
            'serial_number'    => ['required', 'string', 'max:100', "unique:equipment,serial_number,{$id}"],
            'type'             => ['required', new Enum(EquipmentType::class)],
            'status'           => ['required', new Enum(EquipmentStatus::class)],
            'imei1'            => ['nullable', 'string', 'max:20'],
            'imei2'            => ['nullable', 'string', 'max:20'],
            'phone_number'     => ['nullable', 'string', 'max:20'],
            'carrier'          => ['nullable', 'string', 'max:100'],
            'ip_address'       => ['nullable', 'ip'],
            'mac_address'      => ['nullable', 'string', 'max:17'],
            'operating_system' => ['nullable', 'string', 'max:100'],
            'storage_capacity' => ['nullable', 'string', 'max:50'],
            'ram'              => ['nullable', 'string', 'max:50'],
            'purchase_date'    => ['nullable', 'date', 'before_or_equal:today'],
            'warranty_expiry'  => ['nullable', 'date'],
            'supplier'         => ['nullable', 'string', 'max:150'],
            'cost'             => ['nullable', 'numeric', 'min:0', 'max:9999999.99'],
            'notes'            => ['nullable', 'string', 'max:2000'],
            'photos'           => ['nullable', 'array', 'max:10'],
            'photos.*'         => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'serial_number.unique' => 'Ya existe otro activo con este número de serie.',
        ];
    }
}
