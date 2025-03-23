<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tags', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('name', 199);
            $table->string('slug', 199);
            $table->string('type', 199)->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tags');
    }
};
