<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToAdminTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('admins', function (Blueprint $table) {
            //
            $table->integer('login_num')->default(0)->comment('登录次数');
            $table->string('ip')->default('::1')->comment('本次登录IP');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('admins', function (Blueprint $table) {
            //
            $table->removeColumn('login_num');
            $table->removeColumn('ip');
        });
    }
}
