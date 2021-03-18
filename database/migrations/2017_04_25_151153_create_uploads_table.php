<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category')->default(0)->comment('类型');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->integer('admin_id')->default(0)->comment('后台用户ID');
            $table->string('url')->default('')->comment('链接地址');
            $table->string('path')->default('')->comment('目录');
            $table->string('type')->default('')->comment('类型');
            $table->string('cip',50)->default('')->comment('上传IP');
            $table->integer('size')->default(0)->comment('大小 KB');
            $table->string('thumb')->default('')->comment('缩略图');
            $table->string('original_name')->default('')->comment('原始名称');
            $table->string('mime')->default('')->comment('mime Type');
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
        Schema::dropIfExists('uploads');
    }
}
