<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('serviceitems', function($table)
        {
            $table->engine = 'InnoDB';
            $table->integer('service_id')->unsigned();
            $table->integer('sort_order');
            $table->string('serviceitem_type', 255)->nullable();
            $table->string('serviceitem_note', 255)->nullable();
            $table->primary(['service_id','sort_order']);
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('serviceitems');
    }
};
