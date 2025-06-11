<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;

class ResearchAndPublicationDetail extends Model
{
    protected $fillable = [
        'employee_id',
        'research_title',
        'note',
    ];
}
