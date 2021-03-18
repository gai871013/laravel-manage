<?php

use Illuminate\Database\Seeder;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('admins')->insert([
            [
                'username' => 'admin',
                'email' => 'wang.gaichao@163.com',
                'password' => bcrypt('admin888'),
                'remember_token' => str_random(10),
                'role_id' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]
        ]);
        factory('App\Models\Admin', 3)->create([
            'password' => bcrypt('123456')
        ]);
    }
}
