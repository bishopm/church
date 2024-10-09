<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('leavedays', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->date('startdate');
            $table->date('enddate');
            $table->integer('numberofdays');
            $table->integer('employee_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('leavedays');
    }
};
