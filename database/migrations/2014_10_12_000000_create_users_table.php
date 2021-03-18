<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username',100)->unique()->comment('用户名');
            $table->string('password')->comment('密码');
            $table->string('email')->comment('邮箱');
            $table->string('name')->nullable()->comment('姓名');
            $table->string('nickname')->nullable()->comment('昵称');
            $table->integer('role_id')->default(0)->comment('角色ID');
            $table->text('action_list')->nullable()->comment('权限列表');
            $table->string('last_ip', 30)->default('::1')->comment('最后登录IP');
            $table->timestamp('last_login')->nullable()->comment('上次登录时间');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
