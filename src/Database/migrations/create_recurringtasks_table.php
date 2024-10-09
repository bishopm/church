<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('recurringtasks', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('description', 255);
            $table->string('frequency', 100);
            $table->integer('taskday');
            $table->integer('individual_id');
            $table->string('visibility', 199)->default('public');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('recurringtasks');
    }
};
