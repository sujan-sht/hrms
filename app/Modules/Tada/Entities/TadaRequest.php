<?php

namespace App\Modules\Tada\Entities;

// use App\Modules\Tada\Entities\TadaBill;

use App\Modules\Employee\Entities\Employee;
use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TadaRequest extends Model
{
    use SoftDeletes;

    const FILE_PATH = '/uploads/tada/request/excels/';

    // protected $table = 'tadas';

    protected $fillable = [
        'title',
        'employee_id',
        'request_code',
        'nep_request_date',
        'eng_request_date',
        'remarks',
        'status',
        'forwarded_to',
        'forwarded_date',
        'forwaded_remarks',
        'accepted_by',
        'accepted_date',
        'fully_settled_by',
        'fully_settled_date',
        'rejected_by',
        'rejected_date',
        'rejected_remarks',
        'created_by',
        'updated_by',
        'is_agree'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function billAmount()
    {
        $billAmount = $this->hasMany(TadaRequestDetail::class, 'tada_request_id')->sum('amount');
        return $billAmount;
    }
    public function tadaPartiallySettled()
    {
        return $this->hasMany(TadaRequestPartiallySettledDetail::class, 'tada_request_id');
    }
    public function partiallySettledAmount()
    {
        $billAmount = $this->hasMany(TadaRequestPartiallySettledDetail::class, 'tada_request_id')->sum('settled_amt');
        return $billAmount;
    }

    public function tadaDetails()
    {
        return $this->hasMany(TadaRequestDetail::class, 'tada_request_id');
    }

    public static function tadaAmountByType($tada_id, $type_id)
    {
        $tada_amount = TadaDetail::where('tada_id', $tada_id)->where('type_id', $type_id)->select('amount')->first();
        return $tada_amount;
    }

    public function getStatus()
    {
        $list = Self::statusList();
        return $list[$this->status];

        // if ($this->status == 1) {
        //     return 'Pending';
        // } else if ($this->status == 2) {
        //     return 'Forwarded';
        // } else if ($this->status == 3) {
        //     return 'Accepted';
        // } else {
        //     return 'Rejected';
        // }
    }

    public function getStatusWithColor()
    {
        $list = Self::statusList();

        switch ($this->status) {
            case '2':
                $color = 'primary';
                break;
            case '3':
                $color = 'success';
                break;
            case '4':
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

    public static function statusList()
    {
        return [
            '1' => 'Pending',
            '2' => 'Forwarded',
            '3' => 'Accepted',
            '4' => 'Rejected'
        ];
    }
}
