<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chords', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('chord', 50);
            $table->string('s1', 10)->nullable();
            $table->string('s2', 10)->nullable();
            $table->string('s3', 10)->nullable();
            $table->string('s4', 10)->nullable();
            $table->string('s5', 10)->nullable();
            $table->string('s6', 10)->nullable();
            $table->string('fret', 10)->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('chords');
    }
};
