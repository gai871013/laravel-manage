<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFollowersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('followers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('app_id')->default(1)->comment('应用ID');
            $table->integer('subscribe')->comment('是否关注');
            $table->integer('subscribe_time')->default(0)->nullable()->comment('关注时间');
            $table->string('openid', 28)->unique()->comment('用户OPENID');
            $table->text('nickname')->nullable()->comment('微信昵称');
            $table->integer('sex')->default(0)->comment('性别');
            $table->string('province')->nullable()->comment('省份');
            $table->string('city')->nullable()->comment('城市');
            $table->string('country')->nullable()->comment('国家');
            $table->string('language')->nullable()->comment('语言');
            $table->string('headimgurl')->nullable()->comment('头像');
            $table->string('unionid')->nullable()->comment('头像');
            $table->string('remark')->nullable()->comment('备注');
            $table->integer('groupid')->nullable()->comment('分组');
            $table->string('tagid_list')->nullable()->comment('用户被打上的标签ID列表');
            $table->integer('status')->default(1)->comment('状态');
            $table->integer('shop_id')->default(0)->comment('店铺ID');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->string('tel', 20)->nullable()->comment('绑定手机号');
            $table->integer('point')->default(0)->comment('积分');
            $table->string('address')->nullable()->comment('地址');
            $table->string('ip')->default('::1')->comment('操作IP');
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
        Schema::dropIfExists('followers');
    }
}
