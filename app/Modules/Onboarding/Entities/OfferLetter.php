<?php

namespace App\Modules\Onboarding\Entities;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class OfferLetter extends Model
{
    protected $fillable = [
        'evaluation_id',
        'join_date',
        'salary',
        'expiry_date',
        'status',
        'created_by',
        'updated_by'
    ];

    /**
     * Relation with evaluation
     */
    public function evaluationModel()
    {
        return $this->belongsTo(Evaluation::class, 'evaluation_id');
    }

    /**
     * Status with color
     */
    public function getStatusWithColor()
    {
        $list = Self::statusList();

        switch ($this->status) {
            case '2':
                $color = 'success';
            break;
            case '3':
                $color = 'danger';
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

    /**
     * Status list
     */
    public static function statusList()
    {
        return [
            '1' => 'Pending',
            '2' => 'Accepted',
            '3' => 'Rejected'
        ];
    }

    /**
     * boot function for user tracking
     */
    public static function boot()
    {
        parent::boot();

        Self::creating(function ($model) {
            $model->created_by = Auth::user()->id;
        });

        Self::updating(function ($model) {
            $model->updated_by = Auth::user()->id;
        });
    }
}
