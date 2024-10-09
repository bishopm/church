<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notices', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->date('servicedate');
            $table->text('details');
            $table->smallInteger('slide')->nullable();
            $table->string('person');
            $table->string('services');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('notices');
    }
};
