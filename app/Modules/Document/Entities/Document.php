<?php

namespace App\Modules\Document\Entities;

use App\Modules\Employee\Entities\Employee;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{

    protected $fillable = [
        'title',
        'description',
        'status',
        'method_type',
        'created_by',
        'updated_by',
        'type'
    ];

    public static function statusList()
    {
        return [
            '10' => 'Inactive',
            '11' => 'Active'
        ];
    }

    /**
     *
     */
    public function getStatusWithColor()
    {
        $list = Self::statusList();

        switch ($this->status) {
            case '10':
                $color = 'danger';
                break;
            case '11':
                $color = 'success';
                break;
            default:
                $color = 'secondary';
                break;
        }

        return [
            'status' => $list[$this->status],
            'color' => $color
        ];
    }

    public function attachments()
    {
        return $this->hasMany(DocumentAttachment::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'document_employees', 'document_id', 'employee_id');
    }

    public function documentEmployee()
    {
        return $this->hasMany(DocumentEmployee::class, 'document_id', 'id');
    }

    public function documentOrganization()
    {
        return $this->hasOne(DocumentOrganization::class, 'document_id', 'id');
    }
}
