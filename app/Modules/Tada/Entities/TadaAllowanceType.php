<?php

namespace App\Modules\Tada\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TadaAllowanceType extends Model
{
	use SoftDeletes;
	
    protected $fillable = [
    	'title',
    	'status',
    	'created_by',
    	'updated_by',
    ];
}
