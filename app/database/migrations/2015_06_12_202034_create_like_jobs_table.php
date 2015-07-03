<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLikeJobsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('like_jobs', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('title', 20);
            $table->string('content', 100);
            $table->string('rule_1', 20);
            $table->string('rule_2', 20);
            $table->string('rule_3', 20)->nullable();
            $table->string('rule_4', 20)->nullable();
            $table->string('rule_5', 20)->nullable();
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
        Schema::drop('like_jobs');
    }

}
