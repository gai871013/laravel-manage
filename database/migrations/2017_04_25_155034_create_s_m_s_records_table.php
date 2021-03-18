<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSMSRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sms_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0)->comment('创建者user_id');
            $table->string('tel',11)->default('')->comment('手机号码');
            $table->string('code',8)->default('')->comment('验证码');
            $table->integer('ctime')->default(0)->comment('创建时间');
            $table->string('cip',30)->default('::1')->comment('创建IP');
            $table->integer('used')->default(0)->comment('是否使用');
            $table->integer('num')->default(0)->comment('使用次数');
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
        Schema::dropIfExists('s_m_s_records');
    }
}
