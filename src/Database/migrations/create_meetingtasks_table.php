<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('meetingtasks', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->text('description');
            $table->integer('individual_id');
            $table->integer('agendaitem_id')->nullable();
            $table->date('duedate')->nullable();
            $table->string('visibility', 199)->default('public');
            $table->string('status', 199)->default('todo');
            $table->string('statusnote', 199)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('meetingtasks');
    }
};
