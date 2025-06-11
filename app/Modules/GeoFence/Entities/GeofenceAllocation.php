<?php

namespace App\Modules\GeoFence\Entities;

use App\Modules\Branch\Entities\Branch;
use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Setting\Entities\Department;
use Illuminate\Database\Eloquent\Model;
use PhpOffice\PhpSpreadsheet\Calculation\Engine\BranchPruner;

class GeofenceAllocation extends Model
{

    protected $fillable = [
        'geofence_id',
        'organization_id',
        'department_id',
        'employee_id',
        'branch_id'
    ];

    public function geofence()
    {
        return $this->belongsTo(GeoFence::class, 'geofence_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
