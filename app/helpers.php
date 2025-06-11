<?php

/**
 * Global helpers file with misc functions.
 */

use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Helpers\HREmployeeHelper;
use Illuminate\Support\Collection;
use App\Modules\User\Entities\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Modules\LeaveYearSetup\Entities\LeaveYearSetup;
use App\Modules\FiscalYearSetup\Entities\FiscalYearSetup;

if (!function_exists('history')) {
    /**
     * Helper to grab the application name.
     *
     * @return mixed
     */
    function history()
    {
        return new App\Modules\History\Repositories\HistoryRepository;
    }
}


if (!function_exists('nepaliToday')) {
    function nepaliToday()
    {
        $year = date('Y');
        $month = date('m');
        $day = date('d');
        $nepali_date_resp = date_converter()->eng_to_nep($year, $month, $day);

        if ($nepali_date_resp['month'] <= 9) {
            $nepali_date_resp['month'] = '0' . $nepali_date_resp['month'];
        }
        if ($nepali_date_resp['date'] <= 9) {
            $nepali_date_resp['date'] = '0' . $nepali_date_resp['date'];
        }
        $today_nepali_date = $nepali_date_resp['year'] . '-' . $nepali_date_resp['month'] . '-' . $nepali_date_resp['date'];

        return $today_nepali_date;
    }
}
if (!function_exists('date_converter')) {
    function date_converter()
    {
        return new App\Modules\Admin\Entities\DateConverter;
    }
}


if (!function_exists('employee_helper')) {
    function employee_helper()
    {
        return new App\Helpers\HREmployeeHelper;
    }
}

if (!function_exists('getEmployeeIds')) {
    function getEmployeeIds()
    {
        return HREmployeeHelper::getEmployeeIds();
    }
}

if (!function_exists('priceFormat')) {
    /**
     * Helper to get price format
     *
     * @return mixed
     */
    function priceFormat($price)
    {
        return number_format($price, 2, '.', ',') . '/-';
    }
}

if (!function_exists('setting')) {
    function setting($key)
    {
        $setting =  \App\Modules\Setting\Entities\Setting::find(1);
        return $setting ? $setting->$key : '';
    }
}

if (!function_exists('leaveYearSetup')) {
    function leaveYearSetup($key)
    {
        $leaveYearSetup =  \App\Modules\LeaveYearSetup\Entities\LeaveYearSetup::where('status', 1)->first();
        return $leaveYearSetup ? $leaveYearSetup->$key : '';
    }
}

if (!function_exists('getStandardDateFormat')) {
    function getStandardDateFormat($date)
    {
        return $date ? date('M d, Y', strtotime($date)) : '';
    }
}

if (!function_exists('paginate')) {

    function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
}

if (!function_exists('getCurrentFiscalYearId')) {
    function getCurrentFiscalYearId()
    {
        $current_fiscal_year_data = FiscalYearSetup::currentFiscalYear();
        return $current_fiscal_year_data ? $current_fiscal_year_data->id : '';
    }
}

if (!function_exists('getCurrentLeaveYearId')) {
    function getCurrentLeaveYearId()
    {
        $current_leave_year_data = LeaveYearSetup::currentLeaveYear();
        return $current_leave_year_data ? $current_leave_year_data->id : '';
    }
}

if (!function_exists('setObjectIdAndName')) {
    function setObjectIdAndName($arrayData = [])
    {
        $returnData = [];
        foreach ($arrayData as $key => $value) {
            $returnData[] = [
                'id' => $key,
                'name' => $value
            ];
        }
        return $returnData;
        // return collect($arrayData)->map(function ($value, $key) {
        //     return [
        //         'id' => $key,
        //         'name' => $value
        //     ];
        // });
    }
}



if (!function_exists('getEmpId')) {
    function getEmpId()
    {
        $emp = [];
        $userTypes = ['supervisor', 'hr', 'division_hr'];
        if (in_array(auth()->user()->user_type, $userTypes)) {
            $empModel = optional(auth()->user())->userEmployer;
            $data = [
                'emp_id' => $empModel->id,
                'org_id' => $empModel->organization_id,
            ];
            return json_encode($empModel);
        }

        // dd($emp);
        // return json_encode($emp);
    }
}

// function getAddressFromLatLong($coordinates)
// {
//     $latitude = $coordinates['lat'];
//     $longitude = $coordinates['long'];

//     $url = "http://maps.google.com/maps/api/geocode/json?latlng=$latitude,$longitude";
//     $geocode = file_get_contents($url);
//     $json = json_decode($geocode);
//     dd($json);

//     dd(env('GOOGLE_MAPS_API_KEY'));
//     $client = new Client(); //GuzzleHttp\Client
//     $result = (string) $client->post(
//         "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $coordinates['lat'] . "," . $coordinates['long'],
//         ['form_params' => ['key' => env('GOOGLE_MAPS_API_KEY')]]
//     )->getBody();
//     $json = json_decode($result);
//     dd($json);
//     $address->lat = $json->results[0]->geometry->location->lat;
//     $address->lng = $json->results[0]->geometry->location->lng;
// }

if (!function_exists('user_with_roles_and_permissions')) {
    function user_with_roles_and_permissions()
    {
        return Cache::has('user_with_roles_and_permissions')
            ? Cache::get('user_with_roles_and_permissions')
            : Cache::rememberForever('user_with_roles_and_permissions', function () {
                return
                    User::with('role.permission')->get();
            });
    }
}

if (!function_exists('can')) {
    function can($currentRoute = '')
    {
        $user = Auth::user();
        if ($user->user_type == 'super_admin') {
            return true;
        }
        $userRoutes = [];

        $users =  user_with_roles_and_permissions();
        $userinfo =  $users->first(function ($u) use ($user) {
            return $u->id == $user->id;
        });

        foreach ($userinfo->role as $roles) {

            foreach ($roles->permission as $permission) {
                $userRoutes[] = $permission->route_name;
            }
        }

        $defaultRoutes = ['login', 'logout', 'dashboard'];
        $userAllowRoutes = array_merge($userRoutes, $defaultRoutes);
        if (in_array($currentRoute, $userAllowRoutes)) {
            return true;
        }

        return false;
    }

    if (!function_exists('emailSetting')) {
        function emailSetting($moduleId)
        {
            $emailSetup =  \App\Modules\Setting\Entities\EmailSetup::where('module_id', $moduleId)->first();
            return $emailSetup ? $emailSetup->status : '10';
        }
    }

    // if(!function_exists('getYesNo')){
    //     function getYesNo($value){
    //         return $value === 11 ? 'Yes' :($value === 10 ? 'No' : '');
    //     }
    // }

    function getUserDetails($id, $modal)
    {
        if ($id == null && $modal == null) {
            return '';
        }

        $user = $modal::where('id', $id)->first();
        if (!$user) {
            return '';
        }
        return $user ?? '';
    }
}
if(!function_exists ('getTotalDaysInLeaveYear')){
    function getTotalDaysInLeaveYear($startDate, $endDate, $calenderType)
    {
        if($calenderType == 'nep'){
            $date1 = Carbon::parse(date_converter()->nep_to_eng_convert($startDate));
            $date2 = Carbon::parse(date_converter()->nep_to_eng_convert($endDate));
        }else{
            $date1 = Carbon::parse($startDate);
            $date2 = Carbon::parse($endDate);
        }
        return $date1->diffInDays($date2, false)+1;
    }
}
