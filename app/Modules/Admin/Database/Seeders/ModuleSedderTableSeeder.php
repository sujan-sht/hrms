<?php

namespace App\Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Nwidart\Modules\Facades\Module;

class ModuleSedderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        $modules = Module::toCollection();

        $mandatory_modules = [
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

        foreach ($modules as $moduleKey => $moduleValue) {

            $existingRecord = DB::table('modules')
                ->where('name', $moduleKey)
                ->first();

            if ($existingRecord) {
                if (in_array($moduleKey, $mandatory_modules)) {
                    DB::table('modules')
                        ->where('name', $moduleKey)
                        ->update(['name' => $moduleKey, 'status' => 1]);
                } else {
                    DB::table('modules')
                        ->where('name', $moduleKey)
                        ->update(['name' => $moduleKey]);
                }
            } else {
                if (in_array($moduleKey, $mandatory_modules)) {
                    DB::table('modules')
                        ->insert(['name' => $moduleKey, 'status' => 1]);
                } else {
                    DB::table('modules')
                        ->insert(['name' => $moduleKey]);
                }
            }
        }

        // $this->call("OthersTableSeeder");
    }
}
