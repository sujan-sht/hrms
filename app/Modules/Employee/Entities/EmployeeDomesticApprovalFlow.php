<?php

namespace App\Modules\Employee\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmployeeDomesticApprovalFlow extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \App\Modules\Employee\Database\factories\EmployeeDomesticApprovalFlowFactory::new();
    }
}
