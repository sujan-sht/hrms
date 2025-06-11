<?php

namespace App\Modules\Insurance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Insurance extends Model
{

    protected $fillable = [
        'insurance_type_id',
        'policy_number',
        'policy_start_date',
        'policy_end_date',
        'policy_maturity_date',
        'sum_assured_amount',
        'company_name',
        'premium_amount',
        'premium_payment_by',
        'total_employees',
        'total_employer',
        'document_upload'
    ];



    /**
     * Get the type that owns the Insurance
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(InsuranceType::class, 'insurance_type_id', 'id');
    }

     public static function boot()
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
