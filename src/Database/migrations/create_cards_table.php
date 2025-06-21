<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cards', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('card');
            $table->string('image')->nullable();
            $table->string('url')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('cards');
    }
};
