<?php

namespace App\Modules\PMS\Entities;

use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;
use App\Modules\PMS\Entities\Kpi;
use App\Modules\Setting\Entities\Department;

class Kra extends Model
{

    protected $fillable = [
        'title',
        'department_id',
        'division_id',
        'date',
        'created_by',
        'updated_by'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function division()
    {
        return $this->belongsTo(Dropdown::class, 'division_id', 'id');
    }

    public function kpis()
    {
        return $this->hasMany(Kpi::class);
    }
    
    public function targetModel()
    {
        return $this->hasMany(Target::class, 'fiscal_year_id');
    }

    public function getKpis($kra_id)
    {
        return Kpi::where('kra_id', $kra_id)->get();
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'division_id', 'id');
    }
}
