<?php

namespace App\Modules\Document\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;

class DocumentEmployee extends Model
{
    protected $fillable = [
        'document_id',
        'employee_id'
    ];

    public function Employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
