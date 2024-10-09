<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('slides', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('filename');
            $table->string('title')->nullable();
            $table->integer('slideshow_id');
            $table->boolean('active');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('slides');
    }
};
