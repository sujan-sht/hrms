<?php

namespace App\Modules\Advance\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Advance\Entities\Advance;

class AdvanceSettlement extends Model
{
    protected $fillable = [
        'advance_id',
        'due_date',
        'nepali_due_date',
        'amount',
        'starting_month',
        'number_of_month'
    ];

    /**
     * Relation with advance
     */
    public function advanceModel()
    {
        return $this->belongsTo(Advance::class, 'advance_id');
    }

    protected static function boot()
    {
        parent::boot();

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
