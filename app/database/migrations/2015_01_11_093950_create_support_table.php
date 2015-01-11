<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSupportTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('support', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('category')->nullable();
			$table->string('title')->nullable();
			$table->string('content')->nullable();
			$table->boolean('status')->default('0');
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
		Schema::drop('support');
	}

}
