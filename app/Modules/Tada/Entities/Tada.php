<?php

namespace App\Modules\Tada\Entities;

use App\Modules\Employee\Entities\Employee;
use App\Modules\Tada\Entities\TadaBill;
use App\Modules\Tada\Entities\TadaRequest;
use App\Modules\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tada extends Model
{
    use SoftDeletes;

    const FILE_PATH = '/uploads/tada/excels/';

    protected $fillable = [
        'title',
        'employee_id',
        'adv_request_id',
        'nep_from_date',
        'nep_to_date',
        'eng_from_date',
        'eng_to_date',
        'excel_file',
        'remarks',
        'status',
        'created_by',
        'updated_by',
        'first_approval_id',
        'forwarded_to',
        'forwarded_date',
        'forwarded_remarks',
        'fully_settled_by',
        'fully_settled_date',
        'request_closed_by',
        'request_closed_date',
        'request_closed_remarks',
        'request_closed_amt',
        'rejected_remarks',
        'rejected_by',
        'rejected_date',
        'is_agree'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    public function advanceRequest()
    {
        return $this->belongsTo(TadaRequest::class, 'id');
    }

    public function billAmount()
    {
        $billAmount = $this->hasMany(TadaDetail::class, 'tada_id')->sum('amount');
        return $billAmount;
    }

    public function bills()
    {
        return $this->hasMany(TadaBill::class, 'tada_id');
    }

    public function tadaDetails()
    {
        return $this->hasMany(TadaDetail::class, 'tada_id');
    }

    public static function tadaAmountByType($tada_id, $type_id)
    {
        $tada_amount = TadaDetail::where('tada_id', $tada_id)->where('type_id', $type_id)->select('amount')->first();
        return $tada_amount;
    }

    public function tadaPartiallySettled()
    {
        return $this->hasMany(TadaPartiallySettledDetail::class, 'tada_id');
    }

    public function forwardedBy()
    {
        return $this->belongsTo(User::class, 'first_approval_id');
    }

    public function partiallySettledAmount()
    {
        $billAmount = $this->hasMany(TadaPartiallySettledDetail::class, 'tada_id')->sum('settled_amt');
        return $billAmount;
    }

    public function getStatus()
    {
        $list = Self::statusList();
        return $list[$this->status];
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
            case '5':
                $color = 'info';
                break;
            case '6':
                $color = 'warning';
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
            '4' => 'Rejected',
            '5' => 'Fully Settled',
            '6' => 'Partially Settled'
        ];
    }
}
