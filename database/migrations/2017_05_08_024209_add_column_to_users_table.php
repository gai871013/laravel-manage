<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('sex')->default(0)->comment('性别');
            $table->string('uid', 20)->default('')->comment('工号');
            $table->string('mobile', 20)->default('')->comment('手机号');
            $table->string('company_name')->default('')->comment('所在单位');
            $table->string('department')->default('')->comment('部门');
            $table->string('position')->default('')->comment('职位');
            $table->text('card_address')->nullable()->comment('身份证住址');
            $table->text('address')->nullable()->comment('现住址');
            $table->date('license_date')->nullable()->comment('驾驶证初次领证日期');
            $table->integer('driving_age')->default(0)->comment('驾龄');
            $table->string('quasi_driving_type', 50)->default('C1')->comment('准驾车型');
            $table->string('nature', 100)->default('')->comment('用工性质');
            $table->date('birthday')->nullable()->comment('出生年月日');
            $table->string('id_number', 18)->default('')->comment('身份证号码');
            $table->string('political_status', 20)->default('')->comment('政治面貌');
            $table->string('cultural_level', 20)->default('')->comment('文化程度');
            $table->string('license_number')->default('')->comment('驾驶证编号');
            $table->date('hire_date')->nullable()->comment('本单位上岗日期');
            $table->string('entry_type', 20)->default('自聘')->comment('入职方式');
            $table->string('hire_status')->default('')->comment('是否在职');
            $table->text('photos')->nullable()->comment('车辆照片');
            $table->text('front_photo')->nullable()->comment('驾驶证正本照片');
            $table->text('copy_photo')->nullable()->comment('驾驶证副本照片');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Users', function (Blueprint $table) {
            $table->removeColumn('sex');
            $table->removeColumn('uid');
            $table->removeColumn('mobile');
            $table->removeColumn('company_name');
            $table->removeColumn('department');
            $table->removeColumn('position');
            $table->removeColumn('card_address');
            $table->removeColumn('address');
            $table->removeColumn('license_date');
            $table->removeColumn('driving_age');
            $table->removeColumn('quasi_driving_type');
            $table->removeColumn('nature');
            $table->removeColumn('birthday');
            $table->removeColumn('id_number');
            $table->removeColumn('political_status');
            $table->removeColumn('cultural_level');
            $table->removeColumn('license_number');
            $table->removeColumn('hire_date');
            $table->removeColumn('entry_type');
            $table->removeColumn('hire_status');
            $table->removeColumn('photos');
            $table->removeColumn('front_photo');
            $table->removeColumn('copy_photo');
        });
    }
}
