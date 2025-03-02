<?php

namespace Modules\Vehicle\src\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Modules\Core\src\Traits\LogsActivity;
use Modules\Customer\src\Models\Customer;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Vehicle extends Model implements HasMedia
{
    use SoftDeletes, HasFactory, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'code',
        'license_plate',
        'vin',
        'make',
        'model',
        'year',
        'color',
        'engine_number',
        'engine_type',
        'transmission',
        'mileage',
        'mileage_unit',
        'notes',
        'status',
        'customer_id',
    ];

    protected $casts = [
        'year' => 'integer',
        'mileage' => 'integer',
    ];

    protected $attributes = [
        'status' => 'active',
        'mileage_unit' => 'km',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($vehicle) {
            $vehicle->uuid = (string) Str::uuid();

            // Generate vehicle code based on the next ID
            $nextId = (static::withTrashed()->max('id') ?? 0) + 1;
            $vehicle->code = 'VEH-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        });
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function serviceHistory()
    {
        return $this->hasMany(ServiceHistory::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function getFullNameAttribute()
    {
        return "{$this->make} {$this->model} ({$this->year})";
    }
}
