<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id')->comment('评论ID');
            $table->string('content')->comment('评论内容');
            $table->integer('like')->default(0)->comment('评论点赞量');
            $table->integer('user_id')->default(0)->comment('评论uiser_id');
            $table->integer('follow_id')->default(0)->comment('如果为微信用户 openid对应ID');
            $table->string('module', 30)->default('news')->comment('模型');
            $table->string('ip', 15)->default('::1')->comment('评论IP');
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
        Schema::drop('comments');
    }
}
