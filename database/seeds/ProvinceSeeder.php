<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $provinceDistrict = base_path('provincesdistricts.sql');
        // dd($provinceDistrict);
        if(!file_exists($provinceDistrict)){
            dd('file not found !!');
        }
        $sql = File::get($provinceDistrict);
        
        // Run the SQL queries
        DB::unprepared($sql);

        $provinceDistrictId = base_path('provincedistrictsid.sql');
        if(!file_exists($provinceDistrictId)){
            dd('file not found !!');
        }
        $sql1 = File::get($provinceDistrictId);
        DB::unprepared($sql1);

        dd('success');
    }
}
