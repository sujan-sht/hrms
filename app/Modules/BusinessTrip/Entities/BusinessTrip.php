<?php

namespace App\Modules\BusinessTrip\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Tada\Entities\TransportationType;
use Illuminate\Database\Eloquent\Model;

class BusinessTrip extends Model
{
    const IMAGE_PATH = 'uploads/businesstrip';

    protected $casts = [
        'foreign_currency_detail' => 'array',
    ];

    const STATUS = [
        '1' => 'Pending',
        '2' => 'Forwarded',
        '3' => 'Approved',
        '4' => 'Rejected',
        '5' => 'Cancelled'
    ];

    const CLAIM_STATUS = [
        '1' => 'Pending',
        '2' => 'Claimed'
    ];

    const TRAVEL_TYPES = [
        '1' => 'DOMESTIC',
        '2' => 'INTERNATIONAL'
    ];

    protected $fillable = [
        'employee_id',
        'title',
        'from_date',
        'to_date',
        'from_date_nep',
        'to_date_nep',
        'status',
        'request_days',
        'eligible_amount',
        'claim_status',
        'remarks',
        'reject_note',
        'type_id',
        'destination',
        'travel_type',
        'purpose',
        'departure',
        'currency_type',
        'note',
        'quantity',
        'foreign_currency_detail',
        'convert_nepali_amount',
        'document',
        'transport_type',
        'designation',
        'advance_amount',
        'converted_amount_npr'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function getStatus()
    {
        return BusinessTrip::STATUS[$this->status ?? 1];
    }

    public function getClaimStatus()
    {
        return BusinessTrip::CLAIM_STATUS[$this->claim_status ?? 1];
    }

    public function type()
    {
        return $this->belongsTo(TravelRequestType::class, 'type_id');
    }

    public function transportType()
    {
        return $this->belongsTo(TransportationType::class, 'transport_type','id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Created post: ' . $model);
        });

        static::updated(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Updated post: ' . $model);
        });

        static::deleted(function ($model) {
            activity()
                ->performedOn($model)
                ->causedBy(auth()->user())
                ->log('Deleted post: ' . $model);
        });
    }
}
