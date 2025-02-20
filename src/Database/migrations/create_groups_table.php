<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('groups', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('groupname', 255);
            $table->string('slug', 255);
            $table->integer('meetingday')->nullable();
            $table->time('meetingtime', 255)->nullable();
            $table->string('grouptype', 255)->default('service');
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->integer('individual_id');
            $table->boolean('publish')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('groups');
    }
};
