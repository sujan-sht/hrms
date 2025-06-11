<?php

namespace App\Modules\Appraisal\Entities;

use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    protected $fillable = ['score','frequency','ability','effectiveness'];
}
