<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('agendaitems', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('meeting_id');
            $table->string('heading', 199);
            $table->integer('sortorder');
            $table->integer('level');
            $table->text('minute')->nullable();
            $table->string('followup', 199)->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('agendaitems');
    }
};
