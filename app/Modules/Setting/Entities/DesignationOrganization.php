<?php

namespace App\Modules\Setting\Entities;

use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;

class DesignationOrganization extends Model
{
    protected $fillable = [
        'designation_id',
        'organization_id'
    ];

    public function organization(){
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function designation(){
        return $this->belongsTo(Designation::class, 'designation_id');
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
