<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coursesessions', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('session', 191)->default(null);
            $table->integer('course_id')->default(null);
            $table->text('notes')->default(null);
            $table->string('video', 191)->default(null);
            $table->string('file', 191)->default(null);
            $table->integer('order')->default(null);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('coursesessions');
    }
};
