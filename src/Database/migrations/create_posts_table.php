<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('posts', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('person_id');
            $table->integer('user_id')->default(null);
            $table->string('title', 191)->default(null);
            $table->string('slug', 191);
            $table->string('image', 191)->default(null);
            $table->text('excerpt')->default(null);
            $table->text('content')->default(null);
            $table->tinyInteger('published')->default(0);
            $table->timestamp('published_at')->nullable()->default(null);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
