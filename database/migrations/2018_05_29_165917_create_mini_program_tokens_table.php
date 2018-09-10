<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateMiniProgramTokensTable.
 */
class CreateMiniProgramTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mini_program_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token', 60)->unique()->nullable()->comment('Token');
            $table->integer('user_id')->nullable()->comment('绑定前台用户编号ID');
            $table->integer('admin_id')->nullable()->comment('绑定后台用户ID');
            $table->integer('follower_id')->nullable()->comment('小程序用户id编号');
            $table->string('openid', 28)->unique()->comment('用户OPENID');
            $table->timestamps();
            $table->timestamp('expires_at')->nullable()->comment('过期日期');
//            $table->index('token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mini_program_tokens');
    }
}
