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
            $table->time('endtime');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('meetings');
    }
};
