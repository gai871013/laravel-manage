<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->string('username', 100)->unique()->comment('用户名');
            $table->string('email')->comment('邮箱');
            $table->string('password')->comment('密码');
            $table->string('name')->nullable()->comment('姓名');
            $table->string('nickname')->nullable()->comment('昵称');
            $table->integer('role_id')->comment('角色ID');
            $table->text('action_list')->nullable()->comment('权限列表');
            $table->string('last_ip')->default('::1')->comment('最后登录IP');
            $table->timestamp('last_login')->nullable()->comment('上次登录时间');
            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
