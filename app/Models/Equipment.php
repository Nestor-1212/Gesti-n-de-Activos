<?php

namespace App\Models;

use App\Enums\EquipmentStatus;
use App\Enums\EquipmentType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'barcode', 'qr_code', 'brand', 'model', 'serial_number',
        'imei1', 'imei2', 'phone_number', 'carrier',
        'ip_address', 'mac_address', 'operating_system',
        'storage_capacity', 'ram', 'type', 'status',
        'purchase_date', 'warranty_expiry', 'supplier', 'cost', 'notes',
    ];

    protected $casts = [
        'purchase_date'   => 'date',
        'warranty_expiry' => 'date',
        'cost'            => 'decimal:2',
        'status'          => EquipmentStatus::class,
        'type'            => EquipmentType::class,
    ];

    // ── Accessors ──────────────────────────────────────────────────────────

    public function getStatusLabelAttribute(): string
    {
        return $this->status?->label() ?? '—';
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type?->label() ?? '—';
    }

    public function getWarrantyStatusAttribute(): string
    {
        if (! $this->warranty_expiry) {
            return 'sin-garantia';
        }

        if ($this->warranty_expiry->isPast()) {
            return 'vencida';
        }

        return $this->warranty_expiry->diffInDays(now()) <= 30
            ? 'por-vencer'
            : 'vigente';
    }

    // ── Scopes ─────────────────────────────────────────────────────────────

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', EquipmentStatus::Available);
    }

    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function (Builder $q) use ($term) {
            $q->where('brand', 'like', "%{$term}%")
              ->orWhere('model', 'like', "%{$term}%")
              ->orWhere('serial_number', 'like', "%{$term}%")
              ->orWhere('barcode', 'like', "%{$term}%")
              ->orWhere('imei1', 'like', "%{$term}%")
              ->orWhere('phone_number', 'like', "%{$term}%");
        });
    }

    public function scopeWarrantyExpiringSoon(Builder $query, int $days = 30): Builder
    {
        return $query->whereNotNull('warranty_expiry')
            ->whereBetween('warranty_expiry', [now(), now()->addDays($days)]);
    }

    // ── Relationships ──────────────────────────────────────────────────────

    public function photos(): HasMany
    {
        return $this->hasMany(EquipmentPhoto::class);
    }

    public function mainPhoto(): HasOne
    {
        return $this->hasOne(EquipmentPhoto::class)->where('is_main', true);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(EquipmentDocument::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class);
    }

    public function activeAssignment(): HasOne
    {
        return $this->hasOne(Assignment::class)->where('status', 'active');
    }

    public function accessories(): HasMany
    {
        return $this->hasMany(Accessory::class);
    }

    public function softwareLicenses(): HasMany
    {
        return $this->hasMany(SoftwareLicense::class);
    }

    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(MaintenanceRecord::class)->latest('maintenance_date');
    }
}
