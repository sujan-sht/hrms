<?php

namespace App\Modules\Shift\Database\Seeders;

use App\Modules\Shift\Entities\Shift;
use App\Modules\Shift\Entities\Group;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class ShiftDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");

        $shift = Shift::create([
            'title' => 'Day',
            'start_time' => '10:00',
            'end_time' => '17:00',
        ]);

  /*       $group = Group::create([
            'org_id' => 1,
            'group_name' => 'Group 1',
            'shift_id' => $shift->id,
            'ot_grace' => '30',
            'ot_grace_period' => 'mins'
        ]); */
    }
}
