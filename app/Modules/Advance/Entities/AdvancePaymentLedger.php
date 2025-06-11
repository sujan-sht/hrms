<?php

namespace App\Modules\Advance\Entities;

use Illuminate\Database\Eloquent\Model;

class AdvancePaymentLedger extends Model
{
    protected $fillable = [
        'advance_id',
        'date',
        'description',
        'debit',
        'credit',
        'balance'
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
