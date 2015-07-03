<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalyticsLikeTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analytics_like', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('daily_like');
            $table->integer('weekly_like');
            $table->integer('monthly_like');
            $table->integer('all_male_like');
            $table->integer('all_female_like');
            $table->integer('daily_male_like');
            $table->integer('weekly_male_like');
            $table->integer('monthly_male_like');
            $table->integer('daily_female_like');
            $table->integer('weekly_female_like');
            $table->integer('monthly_female_like');
            $table->integer('all_male_accept_ratio');
            $table->integer('all_female_accept_ratio');
            $table->float('average_like_duration');
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
        Schema::drop('analytics_like');
    }

}
