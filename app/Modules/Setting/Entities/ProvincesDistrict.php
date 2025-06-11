<?php

namespace App\Modules\Setting\Entities;

use App\Modules\Employee\Entities\District;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProvincesDistrict extends Model
{

    protected $table = 'provinces_districts';


    protected $fillable = ['title', 'district_id'];

    public function getDistrictIdAttribute($value)
    {
        return json_decode($value, true);
    }

    public function setDistrictIdAttribute($value)
    {
        $this->attributes['district_id'] = json_encode($value);
    }

    public function districts()
    {
        return District::whereIn('id', $this->district_id)->get();

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
