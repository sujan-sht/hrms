<?php

namespace App\Modules\BusinessTrip\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TravelRequestType extends Model
{
    const STATUS = [
        '10' => 'Inactive',
        '11' => 'Active',
    ];

    protected $fillable = [
        'title',
        'amount',
        'status'
    ];

    public function getStatus()
    {
        return TravelRequestType::STATUS[$this->status ?? 10];
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
