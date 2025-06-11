<?php

namespace App\Modules\Employee\Entities;

use App\Modules\Dropdown\Entities\Dropdown;
use Illuminate\Database\Eloquent\Model;

class EmergencyDetail extends Model
{
    protected $fillable = ['employee_id','name','phone1','phone2','address','relation','note'];

    public function relationInfo()
    {
        return $this->belongsTo(Dropdown::class,'relation');
    }
    public function getRelationTypeTitleAttribute()
    {
        $title = '';

        if($this->relation) {
            $list = Self::relationType();
            $title = $list[$this->relation];
        }

        return $title;
    }

    public static function getRelationTypeId($string)
    {
        $id = null;

        if($string) {
            $list = array_flip(Self::relationType());
            $id = $list[$string];
        }

        return $id;
    }

    public static function relationType()
    {
        return [
            '1' => 'Grand Father',
            '2' => 'Grand Mother',
            '3' => 'Father',
            '4' =>  'Mother',
            '5' =>  'Brother',
            '6' =>  'Sister',
            '7' =>  'Son',
            '8' =>  'Daughter',
            '9' =>  'Spouse'
        ];
    }

     protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model->name);
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ' . $model->name);
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ' . $model->name);
        });
    }

}
