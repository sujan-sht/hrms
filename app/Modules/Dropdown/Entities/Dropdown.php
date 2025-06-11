<?php

namespace App\Modules\Dropdown\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Dropdown\Entities\Field;

class Dropdown extends Model
{
    protected $fillable = [

        'fid',
        'dropvalue',
    ];

    public function dropdownField()
    {
        return $this->belongsTo(Field::class, 'fid');
    }

    public function getDesignations()
    {
        $field = Field::where('slug', '=', 'designation')->first();
        if ($field) {
            return $field->dropdownValue->pluck('dropvalue', 'id');
        }
        return [];
    }
}
