<?php

namespace App\Modules\Document\Entities;

use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Setting\Entities\Department;
use Illuminate\Database\Eloquent\Model;

class DocumentOrganizationDepartment extends Model
{

    protected $fillable = [
        'document_organization_id',
        'department_id'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
}
