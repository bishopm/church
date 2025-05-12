<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('forms', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 255);
            $table->string('orientation', 255);
            $table->string('width', 255);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('forms');
    }
};
