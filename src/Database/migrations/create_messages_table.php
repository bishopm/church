<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('messages', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('message');
            $table->integer('individual_id')->nullable();
            $table->timestamp('messagetime')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('messages');
    }
};
