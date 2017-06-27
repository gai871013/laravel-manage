<?php

use Illuminate\Database\Seeder;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('roles')->insert([
            [
                "id" => 1,
                "name" => "超级管理员",
                "action_list" => "all",
                "describe" => "超级管理员",
                "level" => 0,
            ],
            [
                "id" => 2,
                "name" => "站点管理员",
                "action_list" => "8",
                "describe" => "站点管理员",
                "level" => 0,
            ],
            [
                "id" => 3,
                "name" => "发布人员",
                "action_list" => null,
                "describe" => "发布人员",
                "level" => 0,
            ],
            [
                "id" => 4,
                "name" => "运营总监",
                "action_list" => null,
                "describe" => "运营总监",
                "level" => 0,
            ],
            [
                "id" => 5,
                "name" => "编辑",
                "action_list" => null,
                "describe" => "编辑",
                "level" => 0,
            ],
            [
                "id" => 6,
                "name" => "总编",
                "action_list" => null,
                "describe" => "总编",
                "level" => 0,
            ]]);
    }
}
