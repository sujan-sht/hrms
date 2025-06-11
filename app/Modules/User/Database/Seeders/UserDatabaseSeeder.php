<?php

namespace App\Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Modules\User\Entities\User;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $user = User::create([
            'ip_address' => '127.0.0.1',
            'username' => 'cms@hrms.com',
            'email' => 'cms@hrms.com',
            'user_type' => 'super_admin',
            'password' => bcrypt('cms@hrms.com'),
            'active' =>'1',
            'first_name' =>'Administrator',
            'parent_id' => '0'
        ]);

        // $this->call("OthersTableSeeder");
    }
}
