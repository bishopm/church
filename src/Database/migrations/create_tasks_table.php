<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('description', 255);
            $table->integer('individual_id');
            $table->integer('agendaitem_id')->nullable();
            $table->date('duedate')->nullable();
            $table->string('visibility', 199)->default('public');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};
