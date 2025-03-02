<?php

namespace Modules\Vehicle\src\Constants;

class FuelType
{
    public const PETROL = 'petrol';
    public const DIESEL = 'diesel';
    public const ELECTRIC = 'electric';
    public const HYBRID = 'hybrid';
    public const LPG = 'lpg';
    public const CNG = 'cng';

    public static function all(): array
    {
        return [
            self::PETROL,
            self::DIESEL,
            self::ELECTRIC,
            self::HYBRID,
            self::LPG,
            self::CNG,
        ];
    }

    public static function options(): array
    {
        return [
            self::PETROL => 'Petrol',
            self::DIESEL => 'Diesel',
            self::ELECTRIC => 'Electric',
            self::HYBRID => 'Hybrid',
            self::LPG => 'LPG',
            self::CNG => 'CNG',
        ];
    }
}
