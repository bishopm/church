<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('songs', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('title');
            $table->string('author')->nullable();
            $table->string('copyright')->nullable();
            $table->string('key')->nullable();
            $table->string('tempo')->nullable();
            $table->string('audio')->nullable();
            $table->string('video')->nullable();
            $table->string('music')->nullable();
            $table->string('musictype')->nullable();
            $table->string('bible')->nullable();
            $table->text('lyrics');
            $table->string('firstline', 255)->nullable();
            $table->string('verseorder', 191)->nullable();
            $table->string('tune', 191)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('songs');
    }
};
