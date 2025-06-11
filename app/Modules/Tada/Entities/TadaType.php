<?php

namespace App\Modules\Tada\Entities;

use Illuminate\Database\Eloquent\Model;

class TadaType extends Model
{
    protected $fillable = [
        'title',
        'status',
        'type'
    ];

    public function getStatusWithColor()
    {
        $list = Self::statusList();

        switch ($this->status) {
            case '1':
                $color = 'success';
                break;
            case '0':
                $color = 'danger';
                break;
            default:
                $color = 'secondary';
                break;
        }

        return [
            'status' => $list[$this->status],
            'color' => $color
        ];
    }
    public function getTypeAttribute($attribute)
    {
        return in_array($attribute ?? null, [0, 1, null]) ?
            [
                0 => 'Request',
                1 => 'Claim',
                null => 'N/A',
            ][$attribute] : null;
    }

    public static function statusList()
    {
        return [
            '1' => 'Active',
            '0' => 'In-Active'
        ];
    }

    public function tadaSubTypes()
    {
        return $this->hasMany(TadaSubType::class, 'tada_type_id');
    }
}
