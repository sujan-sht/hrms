<?php

namespace App\Modules\Setting\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Nwidart\Modules\Facades\Module;

class ModuleController extends Controller
{
    public function index()
    {
        if (auth()->user()->id != 1) {
            abort('404');
        }

        $data['mandatory_modules'] = [
            'Admin',
            'Branch',
            'Dropdown',
            'Employee',
            'Leave',
            'Notification',
            'Organization',
            'Setting',
            'User',
            'Shift',
            'Attendance',
            'FiscalYearSetup'
        ];
        $data['active_modules'] = DB::table('modules')->where('status', 1)->get();
        // dd($data['active_modules']);
        // $data['inactive_modules'] = Module::getByStatus(0);
        $data['inactive_modules'] = DB::table('modules')->where('status', 0)->get();

        return view('setting::module.index', $data);
    }

    public function appModule()
    {
        if (auth()->user()->id != 1) {
            abort('404');
        }

        $data['mandatory_modules'] = ['Admin', 'Branch', 'Dropdown', 'Employee', 'Leave', 'Notification', 'Organization', 'Setting', 'User', 'Shift', 'Attendance', 'FiscalYearSetup'];
        $data['modules'] = DB::table('modules')->get();

        return view('setting::module.appModule', $data);
    }

    function update(Request $request)
    {
        if (Route::is('module.apiModuleUpdate')) {
            $inputData = $request->all();
            $modules = DB::table('modules')->get();
            foreach ($modules as $moduleKey => $moduleValue) {

                if (array_key_exists($moduleValue->name, $inputData['modules'])) {
                    DB::table('modules')
                        ->where('name', $moduleValue->name)
                        ->update(['app_status' => 1]);
                } else {
                    DB::table('modules')
                        ->where('name', $moduleValue->name)
                        ->update(['app_status' => 0]);
                }
            }
        } else {
            $inputData = $request->all();
            $modules = DB::table('modules')->get();
            foreach ($modules as $moduleKey => $moduleValue) {

                if (array_key_exists($moduleValue->name, $inputData['modules'])) {
                    DB::table('modules')
                        ->where('name', $moduleValue->name)
                        ->update(['status' => 1]);
                } else {
                    DB::table('modules')
                        ->where('name', $moduleValue->name)
                        ->update(['status' => 0]);
                }
            }
        }
        // $moduleCollections = Module::toCollection()->toArray();
        // foreach ($moduleCollections as $key => $moduleCollection) {
        //     $getModule = Module::findOrFail($key);
        //     if (array_key_exists($key, $inputData['modules'])) {
        //         $getModule->enable();
        //     } else {
        //         $getModule->disable();
        //     }
        // }



        toastr()->success('Module Updated Successfully');
        return redirect()->back();
    }

    // public function update(Request $request)
    // {
    //     $inputData = $request->all(); // Retrieve all input data from the request

    //     // Fetch all modules from the database
    //     $modules = DB::table('modules')->get();

    //     foreach ($modules as $module) {
    //         $moduleName = $module->name;

    //         // Check if module exists in input data
    //         if (isset($inputData['modules'][$moduleName])) {
    //             // Update status
    //             DB::table('modules')
    //                 ->where('name', $moduleName)
    //                 ->update([
    //                     'status' => isset($inputData['modules'][$moduleName]['status']) ? 1 : 0,
    //                     'app_status' => isset($inputData['modules'][$moduleName]['app_status']) ? 1 : 0,
    //                 ]);
    //         } else {
    //             // If module is not in input data, set both statuses to 0
    //             DB::table('modules')
    //                 ->where('name', $moduleName)
    //                 ->update([
    //                     'status' => 0,
    //                     'app_status' => 0,
    //                 ]);
    //         }
    //     }

    //     toastr()->success('Module Updated Successfully');
    //     return redirect()->back();
    // }

}
