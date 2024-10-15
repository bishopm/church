<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('individual_rosteritem', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('individual_id');
            $table->integer('rosteritem_id');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('individual_rosteritem');
    }
};