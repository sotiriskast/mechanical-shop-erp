<?php

namespace Modules\Vehicle\src\Constants;

class ServiceType
{
    public const REGULAR_MAINTENANCE = 'regular_maintenance';
    public const OIL_CHANGE = 'oil_change';
    public const BRAKE_SERVICE = 'brake_service';
    public const TIRE_CHANGE = 'tire_change';
    public const ENGINE_REPAIR = 'engine_repair';
    public const BODY_REPAIR = 'body_repair';
    public const ELECTRICAL_REPAIR = 'electrical_repair';
    public const MOT_CHECK = 'mot_check';
    public const DIAGNOSIS = 'diagnosis';
    public const OTHER = 'other';

    public static function all(): array
    {
        return [
            self::REGULAR_MAINTENANCE,
            self::OIL_CHANGE,
            self::BRAKE_SERVICE,
            self::TIRE_CHANGE,
            self::ENGINE_REPAIR,
            self::BODY_REPAIR,
            self::ELECTRICAL_REPAIR,
            self::MOT_CHECK,
            self::DIAGNOSIS,
            self::OTHER,
        ];
    }

    public static function options(): array
    {
        return [
            self::REGULAR_MAINTENANCE => 'Regular Maintenance',
            self::OIL_CHANGE => 'Oil Change',
            self::BRAKE_SERVICE => 'Brake Service',
            self::TIRE_CHANGE => 'Tire Change',
            self::ENGINE_REPAIR => 'Engine Repair',
            self::BODY_REPAIR => 'Body Repair',
            self::ELECTRICAL_REPAIR => 'Electrical Repair',
            self::MOT_CHECK => 'MOT Check',
            self::DIAGNOSIS => 'Diagnosis',
            self::OTHER => 'Other',
        ];
    }
}
