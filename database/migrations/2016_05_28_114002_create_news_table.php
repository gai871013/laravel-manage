<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id')->comment('文章ID');
            $table->string('title')->comment('文章标题');
            $table->string('pinyin')->nullable()->comment('文章拼音标题');
            $table->string('english')->nullable()->comment('文章英文标题');
            $table->smallInteger('cat_id')->default(0)->comment('所属栏目');
            $table->mediumText('description')->nullable()->comment('描述');
            $table->string('thumb')->default('')->nullable()->comment('缩略图');
            $table->char('style', 24)->default('')->nullable()->comment('标题样式');
            $table->string('meta_keywords', 50)->default('')->nullable()->comment('关键词');
            $table->string('meta_desc', 120)->default('')->nullable()->comment('优化描述');
            $table->tinyInteger('posids')->default(0)->nullable()->comment('推荐位ID');
            $table->string('url')->default('')->nullable()->comment('文章URL/文章链接地址');
            $table->tinyInteger('sort')->default(0)->nullable()->comment('排序');
            $table->tinyInteger('status')->default(1)->nullable()->comment('文章状态 1:正常 -1:已删除 0:待审核');
            $table->boolean('islink')->nullable()->default(0)->comment('是否为链接');
            $table->text('content')->nullable()->comment('内容');
            $table->tinyInteger('paytype')->default(0)->nullable()->comment('支付方式');
            $table->tinyInteger('vote_id')->default(0)->nullable()->comment('投票ID');
            $table->tinyInteger('allow_comment')->default(1)->nullable()->comment('是否允许评论');
            $table->string('copyfrom', 100)->default('')->nullable()->comment('文章来源');
            $table->string('template', 50)->default('')->nullable()->comment('模板');
            $table->integer('read')->default(0)->nullable()->comment('阅读数量');
            $table->integer('like')->default(0)->nullable()->comment('点赞数量');
            $table->timestamp('deleted_at')->nullable()->comment('删除时间');
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
        Schema::drop('articles');
    }
}
