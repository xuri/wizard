<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUniversityTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('university', function(Blueprint $table)
		{
			$table->increments('id');
	        $table->string('province', 30)->nullable();
	        $table->string('city', 30);
	        $table->string('university_type', 10);
	        $table->string('university', 30);
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
		Schema::drop('university');
	}

}
