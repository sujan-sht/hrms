<?php

namespace App\Modules\Poll\Entities;

use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Organization\Entities\Organization;
use App\Modules\Setting\Entities\Department;
use App\Modules\Setting\Entities\Level;
use Illuminate\Database\Eloquent\Model;

class PollParticipant extends Model
{

    protected $fillable = [
        'poll_id',
        'organization_id',
        'department_id',
        'level_id'
    ];
    
    public function poll()
    {
        return $this->belongsTo(Poll::class, 'poll_id');
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
