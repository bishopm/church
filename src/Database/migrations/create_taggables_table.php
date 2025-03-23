<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('taggables', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('taggable_id');
            $table->integer('tag_id');
            $table->integer('taggable_type');
            $table->primary(['taggable_id','tag_id','taggable_type']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('taggables');
    }
};
