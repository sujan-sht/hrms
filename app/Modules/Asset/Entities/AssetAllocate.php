<?php

namespace App\Modules\Asset\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;

class AssetAllocate extends Model
{
    protected $fillable = [
        'employee_id',
        'asset_id',
        'quantity',
        'allocated_date',
        'allocated_by',
        'return_date'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'allocated_by');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function assetAllocateAttachment()
    {
        return $this->hasMany(AssetAllocateAttachment::class, 'asset_allocate_id');
    }
}
