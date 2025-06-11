<?php

namespace App\Modules\Payroll\Entities;

use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BonusSetup extends Model
{
    protected $fillable = [
        'organization_id',
        'title',
        'short_name',
        'description',
        'method',
        'amount',
        'percentage',
        'salary_type',
        'order',
        'status',
        'one_time_settlement'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    public static function statusList()
    {
        return [
            '10' => 'Inactive',
            '11' => 'Active',
        ];
    }

    public function getStatusWithColor()
    {
        $list = Self::statusList();

        switch ($this->status) {
            case '11':
                $color = 'success';
            break;
            case '10':
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

    public static function methodList()
    {
        return [
            '1' => 'Fixed',
            '2' => 'Percentage',
            '3' => 'Manual'
        ];
    }

    public function getMethod()
    {
        $list = Self::methodList();

        return [
            'method' => $list[$this->method],
        ];
    }
    public function bonusDetail(){
        return $this->hasMany(BonusSetupReferenceSalaryType::class,'bonus_setup_id','id');
    }

     protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ');
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ');
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ' . $model);
        });
    }

}
