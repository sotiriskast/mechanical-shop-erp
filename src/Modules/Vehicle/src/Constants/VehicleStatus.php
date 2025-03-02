<?php

namespace Modules\Vehicle\src\Constants;

class VehicleStatus
{
    public const string ACTIVE = 'active';
    public const string INACTIVE = 'inactive';
    public const string SOLD = 'sold';
    public const string SCRAPPED = 'scrapped';

    public static function all(): array
    {
        return [
            self::ACTIVE,
            self::INACTIVE,
            self::SOLD,
            self::SCRAPPED,
        ];
    }

    public static function options(): array
    {
        return [
            self::ACTIVE => 'Active',
            self::INACTIVE => 'Inactive',
            self::SOLD => 'Sold',
            self::SCRAPPED => 'Scrapped',
        ];
    }
}
