<?php

namespace App\Modules\PMS\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Modules\PMS\Entities\Kra;
use App\Modules\PMS\Entities\Target;



class Kpi extends Model
{

    protected $fillable = [
        'kra_id',
        'title',
        'date',
        'created_by',
        'updated_by'
    ];

    public function kraInfo()
    {
        return $this->belongsTo(Kra::class, 'kra_id');
    }

    public function getTargets($kra_id, $kpi_id)
    {
        return Target::where('kra_id', $kra_id)->where('kpi_id', $kpi_id)->first();
    }

    public function target()
    {
        return $this->hasOne(Target::class);
    }
}
