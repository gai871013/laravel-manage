<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmsTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content')->nullable()->comment('模版内容');
            $table->integer('admin_id')->default(0)->comment('管理员ID');
            $table->string('url')->default('')->comment('接口地址');
            $table->string('username')->default('')->comment('用户名');
            $table->string('password')->default('')->comment('password');
            $table->string('type')->default('default')->comment('类型');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sms_templates');
    }
}
