<?php

namespace App\Modules\Document\Entities;

use App\Modules\Organization\Entities\Organization;
use Illuminate\Database\Eloquent\Model;

class DocumentOrganization extends Model
{

    protected $fillable = [
        'document_id',
        'organization_id'
    ];

    public function documentOrganizationDepartment()
    {
        return $this->hasMany(DocumentOrganizationDepartment::class, 'document_organization_id', 'id');
    }

    public function Organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id', 'id');
    }

}
