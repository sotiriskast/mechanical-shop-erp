<?php


namespace Modules\Customer\src\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Support\Str;
use Modules\Core\src\Traits\LogsActivity;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

//use Modules\Vehicle\src\Models\Vehicle;

class Customer extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, LogsActivity,hasFactory;

    protected $fillable = [
        'code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'company_name',
        'vat_number',
        'tax_office',
        'billing_address',
        'shipping_address',
        'city',
        'postal_code',
        'country',
        'notes',
        'is_active'
    ];
    protected $attributes = [
        'country' => 'Cyprus',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($customer) {
            $customer->uuid = (string) Str::uuid();

            // Generate customer code based on the next ID
            $nextId = (static::withTrashed()->max('id') ?? 0) + 1;
            $customer->code = 'CUS-' . str_pad($nextId, 6, '0', STR_PAD_LEFT);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

}
