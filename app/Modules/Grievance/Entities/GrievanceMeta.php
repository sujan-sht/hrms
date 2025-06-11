<?php

namespace App\Modules\Grievance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GrievanceMeta extends Model
{
    public $timestamps = false;
    protected $fillable = ['grievance_id', 'subject_type', 'key', 'value'];

}
