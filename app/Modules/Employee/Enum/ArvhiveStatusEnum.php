<?php
namespace App\Modules\Employee\Enum;

class ArvhiveStatusEnum {
    const RESIGNED = 1;
    const RETIRED = 2;
    const TERMINATED = 3;
    const LAYOFF = 4;
    const OTHER = 5;

    public static function getNameByValue($value) {
        $map = [
            self::RESIGNED => 'RESIGNED',
            self::RETIRED => 'RETIRED',
            self::TERMINATED => 'TERMINATED',
            self::LAYOFF => 'LAYOFF',
            self::OTHER => 'OTHER'
        ];

        return isset($map[$value]) ? $map[$value] : null; 
    }
}


