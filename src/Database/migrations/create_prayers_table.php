<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('prayers', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('title', 191);
            $table->string('copyright', 191)->nullable();
            $table->text('words');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('prayers');
    }
};
