<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pages', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('title', 191);
            $table->string('slug', 191);
            $table->string('image', 191)->nullable();;
            $table->text('content')->default(null);
            $table->tinyInteger('published')->default(0);
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pages');
    }
};
