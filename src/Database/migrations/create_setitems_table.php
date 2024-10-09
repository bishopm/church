<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('setitems', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('service_id');
            $table->integer('setitemable_id');
            $table->string('setitemable_type', 199);
            $table->integer('sortorder');
            $table->string('note', 199);
            $table->string('extra', 199)->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('setitems');
    }
};
