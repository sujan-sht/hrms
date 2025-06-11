<?php

namespace App\Modules\Payroll\Entities;

use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxExcludeSetup extends Model
{
    protected $fillable = ['organization_id','title','short_name','order','description','type','status'];

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    public static function statusList()
    {
        return [
            '11' => 'Active',
            '10' => 'Inactive',
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
    public static function typeList()
    {
        return [
            '1' => 'Income',
            '2' => 'Deduction',
        ];
    }

    public function getType()
    {
        $list = Self::typeList();

        // switch ($this->method) {
        //     case '1':
        //         $color = 'success';
        //     break;
        //     case '2':
        //         $color = 'secondary';
        //     break;
        //     default:
        //         $color = '';
        //     break;
        // }

        return [
            'type' => $list[$this->type],
            // 'color' => $color
        ];
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
