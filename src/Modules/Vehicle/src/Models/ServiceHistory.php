<?php

namespace Modules\Vehicle\src\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\src\Traits\LogsActivity;
use Modules\Vehicle\Database\Factories\ServiceHistoryFactory;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ServiceHistory extends Model implements HasMedia
{
    use SoftDeletes, HasFactory, InteractsWithMedia, LogsActivity;

    protected $table = 'vehicle_service_history';

    protected $fillable = [
        'vehicle_id',
        'service_date',
        'service_type',
        'description',
        'mileage',
        'mileage_unit',
        'technician_name',
        'cost',
        'status',
        'notes',
        'work_order_id',
    ];

    protected $casts = [
        'service_date' => 'datetime',
        'mileage' => 'integer',
        'cost' => 'decimal:2',
    ];

    protected $attributes = [
        'mileage_unit' => 'km',
        'status' => 'completed',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory()
    {
        return ServiceHistoryFactory::new();
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function workOrder()
    {
        // Will be implemented when Workshop module is created
        // return $this->belongsTo(WorkOrder::class);
        return null;
    }
}
