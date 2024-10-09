<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('enneagram', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('question', 255);
            $table->integer('ptype');
            $table->integer('num');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('enneagram');
    }
};
