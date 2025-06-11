<?php

namespace App\Modules\Asset\Entities;

use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{

    protected $fillable = [
        'title',
        'description',
        'created_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });

        self::updating(function($model){
            // ... code here
        });
    }
}
