<?php

namespace App\Modules\Asset\Entities;

use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class AssetQuantity extends Model
{

    protected $fillable = [
        'asset_id',
        'code',
        'quantity',
        'remaining_quantity',
        'expiry_date',
        'created_by'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            $model->created_by = auth()->user()->id;
        });
    }
}
