<?php

namespace App\Modules\Employee\Database\Seeders;

use App\Modules\Employee\Entities\Language;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $languages = [
            [
                'name' => 'Nepali'
            ],
            [
                'name' => 'English'
            ],
            [
                'name' => 'Hindi'
            ]
        ];

        foreach ($languages as $value) {
            Language::updateOrCreate(['name' => $value['name']], $value);
        }
    }
}
