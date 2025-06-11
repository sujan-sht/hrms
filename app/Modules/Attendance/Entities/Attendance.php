<?php

namespace App\Modules\Attendance\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Modules\Employee\Entities\Employee;
use App\Modules\Organization\Entities\Organization;

class Attendance extends Model
{
    const MONTHS = [
        '1' => 'January',
        '2' => 'February',
        '3' => 'March',
        '4' => 'April',
        '5' => 'May',
        '6' => 'June',
        '7' => 'July',
        '8' => 'August',
        '9' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December',
    ];

    const NEPALI_MONTHS = [
        '1' => 'Baishak',
        '2' => 'Jestha',
        '3' => 'Ashad',
        '4' => 'Shrawan',
        '5' => 'Bhadra',
        '6' => 'Ashwin',
        '7' => 'kartik',
        '8' => 'Mangshir',
        '9' => 'Poush',
        '10' => 'Magh',
        '11' => 'Falgun',
        '12' => 'Chaitra',
    ];

    protected $fillable = [
        'org_id',
        'emp_id',
        'date',
        'nepali_date',
        'checkin',
        'checkout',
        'checkin_from',
        'checkout_from',
        'total_working_hr',
        'location',
        'lat',
        'long',
        'checkin_status',
        'checkout_status',
        'fieldwork_status',
        'ot_status',
        'ot_hr',
        'checkin_original',
        'checkout_original',
        'checkin_coordinates',
        'checkout_coordinates',
        'late_arrival_in_minutes',
        'is_checkin_next_day',
        'early_departure_in_minutes',
        'actual_ot'
    ];

    protected $casts = [
        'checkin_coordinates' => 'json',
        'checkout_coordinates' => 'json',
    ];


    public function employee()
    {
        return $this->belongsTo(Employee::class, 'emp_id', 'id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'org_id', 'id');
    }

    /**
     *
     */
    public static function getCount()
    {
        $authUser = auth()->user();
        if ($authUser->user_type == 'division_hr') {
            $result = Attendance::where('org_id', optional($authUser->userEmployer)->organization_id)->where('date', date('Y-m-d'))->count();
        } else {
            $result = Attendance::where('date', date('Y-m-d'))->count();
        }

        return $result;

    }

    public static function saveIrregularAttendanceLog($data)
    {
        $model = Attendance::getIrregularAttendanceLog($data['employeeId']);

        if ($data['type'] == 4) {
            if (!empty($model)) {
                $model->total_late_arrival_days = $model->total_late_arrival_days + $data['numberOfDays'];
                $model->save();
            } else {
                IrregularAttendanceLog::create(
                    [
                        'employee_id' =>  $data['employeeId'],
                        'total_late_arrival_days' => $data['numberOfDays'],
                        'total_early_departure_days' => 0
                    ]
                );
            }
        } elseif ($data['type'] == 3) {
            if (!empty($model)) {
                $model->total_early_departure_days = $model->total_early_departure_days + $data['numberOfDays'];
                $model->save();
            } else {
                IrregularAttendanceLog::create(
                    [
                        'employee_id' =>  $data['employeeId'],
                        'total_late_arrival_days' => 0,
                        'total_early_departure_days' => $data['numberOfDays']
                    ]
                );
            }
        }
    }

    public static function getIrregularAttendanceLog($empId)
    {
        $model = [];
        $model = IrregularAttendanceLog::where('employee_id', $empId)->first();
        return $model;
    }

    public function getCoordinatesAttributes()
    {
        $checkIn = $checkOut = [];
        if ($this->checkin_coordinates && $this->checkin_from == 'app') {

            $checkInCoordinates = $this->checkin_coordinates;
            $checkInCoordinates += array('color' => 'green', 'type' => 'Check in');
            // getAddressFromLatLong($checkInCoordinates);
            $checkIn[0] = $checkInCoordinates;
        }
        if ($this->checkout_coordinates && $this->checkout_from == 'app') {
            $checkOutCoordinates = $this->checkout_coordinates;
            $checkOutCoordinates += array('color' => 'red', 'type' => 'Check out');
            $checkOut[1] = $checkOutCoordinates;
        }
        $locations = array_merge($checkIn, $checkOut);
        return json_encode($locations);
        // return $locations;
        // $this->coordinates = json_encode($locations);
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
