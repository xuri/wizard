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
	        $table->integer('province_id')->default(0);
	        $table->string('university', 255)->nullable();
	        $table->integer('count')->default(0);
	        $table->integer('status')->default(0);
	        $table->timestamp('deleted_at')->nullable();
	        $table->timestamp('open_at')->nullable();
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
