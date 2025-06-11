<?php

namespace App\Modules\EmployeeRequest\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Modules\EmployeeRequest\Entities\EmployeeRequestType;

class EmployeeRequestDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $requestType = EmployeeRequestType::create([
            'title' => 'Benefit Type',
            'status' => '1',
            'created_by' => '1',
            'updated_by' => '1'
        ]);

        $requestType = EmployeeRequestType::create([
            'title' => 'Travel Expenses Claim',
            'status' => '1',
            'created_by' => '1',
            'updated_by' => '1'
        ]);
        // $this->call("OthersTableSeeder");
    }
}
