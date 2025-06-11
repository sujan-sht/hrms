<?php

namespace App\Modules\Appraisal\Database\Seeders;

use App\Modules\Appraisal\Entities\Score;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class ScoreSeederTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 5; $i++)
        {
            Score::create([
                'score' => $i,
            ]);
        }
    }
}
