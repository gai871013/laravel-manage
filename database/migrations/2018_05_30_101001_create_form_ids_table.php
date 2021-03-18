<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateFormIdsTable.
 */
class CreateFormIdsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('form_ids', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->comment('用户ID');
            $table->integer('follower_id')->comment('用户follower表的ID');
            $table->string('openid', 28)->comment('用户OPENID');
            $table->string('formId')->comment('formId');
            $table->timestamp('expire_at')->comment('过期时间');
            $table->timestamps();
            $table->timestamp('deleted_at')->nullable()->comment('使用时间、过期自动删除时间');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('form_ids');
	}
}
