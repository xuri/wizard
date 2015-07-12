<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSystemTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('system', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('name');
            $table->string('secret')->nullable();
            $table->string('sid')->nullable();
            $table->string('token')->nullable();
            $table->string('expires_in')->nullable();
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
        Schema::drop('system');
    }

}
