<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('meetings', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('details');
            $table->dateTime('meetingdatetime');
            $table->integer('venue_id');
            $table->integer('group_id');
            $table->json('attendance');
            $table->time('endtime');
            $table->dateTime('nextmeeting')->nullable();
            $table->tinyInteger('calendar')->nullable()->default(null);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('meetings');
    }
};
