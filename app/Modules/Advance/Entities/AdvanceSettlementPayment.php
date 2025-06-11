<?php

namespace App\Modules\Advance\Entities;

use Illuminate\Database\Eloquent\Model;

class AdvanceSettlementPayment extends Model
{
    protected $fillable = [
        'advance_id',
        'date',
        'nepali_date',
        'amount',
        'remark',
        'status',
        'created_by'
    ];

    /**
     * Relation with advance
     */
    public function advanceModel()
    {
        return $this->belongsTo(Advance::class, 'advance_id');
    }

    /**
     *
     */
    public function getStatusDetailAttribute()
    {
        $list = Self::statusList();

        switch ($this->status) {
            case '11':
                $color = 'success';
            break;
            default:
                $color = 'secondary';
            break;
        }

        return [
            'title' => $list[$this->status],
            'color' => $color
        ];
    }

    /**
     * Status list
     */
    public static function statusList()
    {
        return [
            10 => 'Pending',
            11 => 'Completed',
        ];
    }

    /**
     * Boot function
     */
    public static function boot()
    {
        Parent::boot();

        Self::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });



        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model);
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ' . $model);
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ' . $model);
        });
    }
}
