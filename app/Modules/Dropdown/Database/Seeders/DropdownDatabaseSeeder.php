<?php

namespace App\Modules\Dropdown\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Dropdown\Entities\Dropdown;
use App\Modules\Dropdown\Entities\Field;
use Illuminate\Support\Str;

class DropdownDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $field = array('User Type', 'Department', 'Designation', 'Level');

        $Dropvalue = array('admin', 'Department', 'Designation', 'Level');


        foreach ($field as $key => $value) {

            $slug = Str::slug($value, '_');

            $field = Field::create([
                'title' => $value,
                'slug' => $slug
            ]);

            $field_data = Field::where('slug', $slug)->first();

            Dropdown::create([
                'fid' => $field_data['id'],
                'dropvalue' => $Dropvalue[$key],
            ]);
        }

        $staticFields = [
            'Blood Group' => ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'],
            'Marital Status' => ['Single', 'Married'],
            'Gender' => ['Male', 'Female']
        ];

        foreach ($staticFields as $key => $staticField) {

            $slug = Str::slug($key, '_');

            Field::create([
                'title' => $key,
                'slug' => $slug
            ]);

            $field_data = Field::where('slug', $slug)->first();
            foreach ($staticField as  $staticValue) {
                Dropdown::create([
                    'fid' => $field_data['id'],
                    'dropvalue' => $staticValue,

                ]);
            }
        }
    }
}
