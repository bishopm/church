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
            $table->date('meetingdatetime');
            $table->integer('venue_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('meetings');
    }
};
