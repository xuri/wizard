<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfileTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('profile', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->string('hobbies')->nullable();
			$table->string('question')->nullable();
			$table->integer('grade')->nullable();
			$table->tinyInteger('constellation')->nullable();
			$table->string('tag_str')->nullable();
			$table->text('self_intro')->nullable();
			$table->string('language', 10)->nullable();
			$table->integer('renew')->nullable();
			$table->integer('crenew')->nullable();
			$table->timestamp('renew_at')->nullable();
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
		Schema::drop('profile');
	}

}
