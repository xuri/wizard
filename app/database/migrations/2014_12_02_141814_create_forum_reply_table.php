<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForumReplyTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('forum_reply', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('comments_id');
			$table->integer('reply_id');
			$table->text('content');
			$table->boolean('block')->default('0');
			$table->timestamp('deleted_at')->nullable();
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
		Schema::drop('forum_reply');
	}

}
