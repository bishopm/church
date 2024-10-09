<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sermons', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('title');
            $table->integer('person_id');
            $table->date('servicedate');
            $table->string('readings')->nullable();
            $table->string('video')->nullable();
            $table->string('audio')->nullable();
            $table->integer('series_id');
            $table->integer('published')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('sermons');
    }
};
