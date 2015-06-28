<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('email')->nullable();
			$table->string('password');
	        $table->string('nickname', 60)->nullable();
	        $table->string('sex', 4)->nullable();
	        $table->string('bio', 255)->nullable();
	        $table->string('openid', 40)->nullable(); // WeChat Open ID
	        $table->string('passcode', 6)->nullable();
	        $table->integer('province_id', 4)->nullable(); // User location province ID
	        $table->integer('born_year', 4)->nullable(); // User born year
	        $table->string('school', 100)->nullable(); // University name
	        $table->integer('points', 10)->default('10'); // Points
	        $table->integer('w_id', 6)->nullable(); // WAP register user ID
	        $table->string('phone')->nullable(); // Mobile phone number
	        $table->string('portrait')->nullable(); // Avatar file name
	        $table->string('remember_token')->nullable();
	        $table->boolean('is_admin')->default('0');
	        $table->boolean('is_verify')->default('0');
	        $table->boolean('block')->default('0');
	        $table->tinyInteger('from')->default('0');
	        $table->timestamp('deleted_at')->nullable();
	        $table->timestamp('activated_at')->nullable();
	        $table->timestamp('signin_at')->nullable();
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
		Schema::drop('users');
	}

}
