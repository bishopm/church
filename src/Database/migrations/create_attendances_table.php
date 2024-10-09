<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->date('attendancedate');
            $table->string('service');
            $table->integer('individual_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};
