<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalyticsForumTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('analytics_forum', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('all_post');
			$table->integer('cat1_post');
			$table->integer('cat2_post');
			$table->integer('cat3_post');
			$table->integer('daily_post');
			$table->integer('cat1_daily_post');
			$table->integer('cat2_daily_post');
			$table->integer('cat3_daily_post');
			$table->integer('daily_male_post');
			$table->integer('daily_female_post');
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
		Schema::drop('analytics_forum');
	}

}
