<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('formitems', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('form_id');
            $table->string('itemtype', 100);
            $table->json('itemdata');
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('formitems');
    }
};
