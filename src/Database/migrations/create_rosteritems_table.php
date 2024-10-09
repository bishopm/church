<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rosteritems', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('rostergroup_id');
            $table->date('rosterdate');
            $table->json('individuals')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('rosteritems');
    }
};
