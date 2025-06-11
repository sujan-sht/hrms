<?php

namespace App\Modules\Insurance\Database\Seeders;

use App\Modules\Insurance\Entities\InsuranceType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class InsuranceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $data = [
            [
                'title' => 'Life Insurance',
                'status' => 1
            ],
            [
                'title' => 'Medical Insurance',
                'status' => 1
            ],
            [
                'title' => 'Accident Insurance',
                'status' => 1
            ]
        ];

        foreach ($data as $value) {
            InsuranceType::updateOrCreate(['title' => $value['title']], $value);
        }
    }
}
