<?php

namespace App\Modules\Employee\Database\Seeders;

use App\Modules\Employee\Entities\Country;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Modules\Employee\Entities\Province;
use App\Modules\Employee\Entities\District;

class EmployeeDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Model::unguard();

        $provinces = array(
            'Province No 1',
            'Madhesh Province',
            'Bagmati Province',
            'Gandaki Pradesh',
            'Lumbini Province',
            'Karnali Pradesh',
            'Sudurpashchim Pradesh'
        );
        $districts = [
            '0' => [
                'Bhojpur',
                'Dhankuta',
                'Ilam',
                'Jhapa',
                'Khotang',
                'Morang',
                'Okhaldhunga',
                'Panchthar',
                'Sankhuwasabha',
                'Solukhumbu',
                'Sunsari',
                'Taplejung',
                'Terhathum',
                'Udayapur'
            ],
            '1' => [
                'Bara',
                'Parsa',
                'Dhanusha',
                'Mahottari',
                'Rautahat',
                'Saptari',
                'Sarlahi',
                'Siraha'
            ],
            '2' => [
                'Bhaktapur',
                'Chitwan',
                'Dhading',
                'Dolakha',
                'Kathmandu',
                'Kavrepalanchok',
                'Lalitpur',
                'Makwanpur',
                'Nuwakot',
                'Ramechhap',
                'Rasuwa',
                'Sindhuli',
                'Sindhupalchok'
            ],
            '3' => [
                'Baglung',
                'Gorkha',
                'Kaski',
                'Lamjung',
                'Manang',
                'Mustang',
                'Myagdi',
                'Nawalpur',
                'Parbat',
                'Syangja',
                'Tanahun'
            ],
            '4' => [
                'Arghakhanchi',
                'Banke',
                'Bardiya',
                'Dang',
                'Eastern Rukum',
                'Gulmi',
                'Kapilavastu',
                'Parasi',
                'Palpa',
                'Pyuthan',
                'Rolpa',
                'Rupandehi'
            ],
            '5' => [
                'Dailekh',
                'Dolpa',
                'Humla',
                'Jajarkot',
                'Jumla',
                'Kalikot',
                'Mugu',
                'Salyan',
                'Surkhet',
                'Western Rukum'
            ],
            '6' => [
                'Achham',
                'Baitadi',
                'Bajhang',
                'Bajura',
                'Dadeldhura',
                'Darchula',
                'Doti',
                'Kailali',
                'Kanchanpur'
            ]
        ];

        foreach ($provinces as $key => $province) {
            $province_created = Province::updateOrCreate([
                'province_name' => $province
            ], [
                'province_name' => $province,
            ]);

            foreach ($districts[$key] as $district) {
                District::updateOrCreate([
                    'province_id' => $province_created->id,
                    'district_name' => $district
                ], [
                    'province_id' => $province_created->id,
                    'district_name' => $district
                ]);
            }
        }
        $this->call(CountryTableSeeder::class);
    }
}
