<?php

namespace App\Providers;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;
use App\Modules\Tada\Repositories\TadaRepository;
use App\Modules\Leave\Repositories\LeaveRepository;
use App\Modules\Setting\Repositories\LevelRepository;
use App\Modules\Tada\Repositories\TadaRequestRepository;
use App\Modules\BusinessTrip\Repositories\BusinessTripRepository;
use App\Modules\Attendance\Repositories\AttendanceRequestRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Guard $auth)
    {
        activity()->causedBy(auth()->user());

        Activity::saving(function (Activity $activity) {
            $user = Auth::user();
                if (!$user) {
                        return;
                    }
            // 1. Define userInfo separately
            $userInfo = [
                'userInfo' => [
                    'ip' => Request::ip(),
                    'full_name' => trim($user->first_name ?? '' . ' ' . $user->middle_name ?? '' . ' ' . $user->last_name ?? ''),
                    'email' => $user->email ?? '',
                    'user_id' => $user->id ?? '',
                ]
            ];

            // 2. Get existing properties (excluding user-related fields if needed)
            $existingProps = $activity->properties ? $activity->properties->toArray() : [];

            // 3. Remove any existing user-related fields from properties
            $filteredProps = collect($existingProps)->except([
                'ip',
                'full_name',
                'email',
                'user_id',
                'userInfo'
            ]);

            // 4. Combine with userInfo FIRST, then other properties
            $activity->properties = collect($userInfo)->merge($filteredProps);
        });

        Blade::directive('hide', function () {
            return "class='d-none'";
        });

        view()->composer('*', function ($view) use ($auth) {
            $activeUserModel = $auth->user();
            if ($activeUserModel) {

                $leaveRepo = new LeaveRepository();
                $attendanceRequest = new AttendanceRequestRepository();
                $tadaClaim = new TadaRepository();
                $tadaRequest = new TadaRequestRepository();
                $businessTrips = new BusinessTripRepository();

                $leaves = $leaveRepo->getEmployeeLeaves()->where('status', 1)->toArray();
                $attendanceRequests = $attendanceRequest->getEmployeeAttendanceRequest($activeUserModel->emp_id)->toArray();
                $claims = $tadaClaim->getEmployeeClaim($activeUserModel->emp_id)->toArray();
                $requests = $tadaRequest->getEmployeeTadaRequest($activeUserModel->emp_id)->toArray();
                $businessTrips = $businessTrips->getEmployeeBusinessTrips($activeUserModel->emp_id)->toArray();

                $mergeArray = array_merge($leaves, $attendanceRequests, $claims, $requests, $businessTrips);
                $result = collect($mergeArray);
                return $view->with('totalPendingApprovals', $result->count());
            }
        });


        Schema::defaultStringLength(191);


        if (Schema::hasTable('modules')) {
            $moduleArray = DB::table('modules')->pluck('status', 'name')->toArray();

            Module::macro('isModuleEnabled', function ($moduleName) use ($moduleArray) {
                if (array_key_exists($moduleName, $moduleArray) && $moduleArray[$moduleName] == 1) {
                    return true;
                }
                return false;
            });
        }
    }
}
