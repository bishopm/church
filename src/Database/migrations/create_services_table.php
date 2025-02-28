<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('services', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->date('servicedate');
            $table->string('servicetime', 191);
            $table->string('sermon_title', 191)->default(null);
            $table->string('audio', 191)->default(null);
            $table->string('video', 191)->default(null);
            $table->string('reading', 191)->default(null);
            $table->integer('series_id')->default(null);
            $table->integer('person_id')->default(null);
            $table->json('tags')->default(null);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('services');
    }
};
