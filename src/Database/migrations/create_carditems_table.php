<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carditems', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('card_id');
            $table->string('itemtype')->nullable();
            $table->json('properties')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('carditems');
    }
};
