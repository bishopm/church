<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->text('event');
            $table->dateTime('eventdate');
            $table->integer('venue_id');
            $table->text('description');
            $table->text('image')->nullable();
            $table->tinyInteger('calendar')->nullable()->default(null);
            $table->integer('published')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
