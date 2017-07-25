<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id')->comment('栏目ID');
            $table->string('module',30)->default('news')->comment('所属模型');
            $table->tinyInteger('type')->default(0)->comment('类型');
            $table->tinyInteger('parent_id')->default(0)->comment('父ID');
            $table->string('arr_parent_id')->default('')->comment('所有父ID');
            $table->boolean('child')->default(0)->comment('是否有子栏目');
            $table->mediumText('child_id')->nullable()->comment('所有子栏目ID');
            $table->string('catname',50)->default('')->comment('栏目名称');
            $table->string('thumb')->default('')->comment('栏目缩略图');
            $table->string('style',24)->default('')->comment('栏目标题样式');
            $table->mediumText('description')->nullable()->comment('栏目描述');
            $table->string('url')->default('')->comment('（外部）链接地址');
            $table->integer('hits')->default(0)->comment('点击数量');
            $table->mediumText('setting')->nullable()->comment('设置');
            $table->tinyInteger('sort')->default(0)->comment('排序');
            $table->boolean('is_menu')->default(1)->comment('是否显示到菜单');
            $table->string('letter',100)->default('')->comment('栏目拼音');
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
        Schema::drop('article_categories');
    }
}
