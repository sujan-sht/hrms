<?php

namespace App\Modules\PMS\Entities;

use App\Modules\FiscalYearSetup\Entities\FiscalYearSetup;
use Illuminate\Database\Eloquent\Model;
use App\Modules\PMS\Entities\Kra;
use App\Modules\PMS\Entities\Kpi;
use App\Modules\PMS\Entities\TargetAchievement;



class Target extends Model
{

    protected $fillable = [
        'kra_id',
        'kpi_id',
        'fiscal_year_id',
        'title',
        'frequency',
        'category',
        'weightage',
        'eligibility',
        'no_of_quarter',
        'remarks',
        'date',
        'created_by',
        'updated_by'
    ];

    public function kraInfo()
    {
        return $this->belongsTo(Kra::class, 'kra_id');
    }

    public function kpiInfo()
    {
        return $this->belongsTo(Kpi::class, 'kpi_id');
    }

    public function fiscalYearInfo()
    {
        return $this->belongsTo(FiscalYearSetup::class, 'fiscal_year_id');
    }

    public function getDetails($target_id, $quarter)
    {
        return TargetAchievement::where('target_id', $target_id)->where('quarter', $quarter)->first();
    }

    public function TargetAttachments()
    {
        return $this->hasMany(TargetAttachment::class, 'target_id');
    }
}
