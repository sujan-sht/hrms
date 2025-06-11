<?php
namespace App\Modules\Setting\Enum;

use MyCLabs\Enum\Enum;

class TravelTypeEnum extends Enum  {
    const Employee=1;
    const Level=2;
    const Designation=3;

    public static function getAllValues()
    {
        return [
            'Employee' => self::Employee,
            'Level' => self::Level,
            'Designation' => self::Designation,
        ];
    }
}