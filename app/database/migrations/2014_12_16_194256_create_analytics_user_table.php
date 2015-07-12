<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnalyticsUserTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('analytics_user', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('all_user');
            $table->integer('daily_active_user');
            $table->integer('weekly_active_user');
            $table->integer('monthly_active_user');
            $table->integer('all_male_user');
            $table->integer('daily_active_male_user');
            $table->integer('weekly_active_male_user');
            $table->integer('monthly_active_male_user');
            $table->integer('all_female_user');
            $table->integer('daily_active_female_user');
            $table->integer('weekly_active_female_user');
            $table->integer('monthly_active_female_user');
            $table->integer('complete_profile_user_ratio');
            $table->integer('from_web');
            $table->integer('from_android');
            $table->integer('from_ios');
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
        Schema::drop('analytics_user');
    }

}
