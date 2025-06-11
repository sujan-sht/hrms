<?php

namespace App\Modules\Survey\Entities;

use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Setting\Entities\Department;
use App\Modules\Setting\Entities\Level;
use Illuminate\Database\Eloquent\Model;

class SurveyParticipant extends Model
{
    protected $fillable = [
        'survey_id',
        'organization_id',
        'department_id',
        'level_id'
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class, 'survey_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }
   
}
