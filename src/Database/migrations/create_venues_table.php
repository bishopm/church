<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('venues', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('venue', 255);
            $table->string('slug', 199);
            $table->text('description');
            $table->decimal('width',4,2);
            $table->decimal('length',4,2);
            $table->string('image', 199)->nullable();
            $table->tinyinteger('publish');
            $table->integer('resource');
            $table->string('colour', 10)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('venues');
    }
};
