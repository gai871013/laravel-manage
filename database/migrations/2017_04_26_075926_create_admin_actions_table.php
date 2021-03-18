<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminActionsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0)->comment('该id项的父id，对应本表的id字段');
            $table->string('code', 100)->default('')->comment('代表权限的英文字符串，对应汉文在语言文件中，如果该字段有某个字符串，就表示有该权限');
            $table->string('lang', 20)->default('')->comment('权限名称');
            $table->string('route', 30)->default('')->nullable()->comment('目录名');
            $table->string('param', 30)->default('')->nullable()->comment('参数');
            $table->integer('enable')->default(1)->comment('是否显示/使用');
            $table->string('remark', 50)->default('')->comment('备注');
            $table->string('icon', 30)->default('')->comment('图标');
            $table->integer('list_order')->default(10)->comment('排序');
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
        Schema::dropIfExists('admin_actions');
    }
}
