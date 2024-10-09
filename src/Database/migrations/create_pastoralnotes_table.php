<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pastoralnotes', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('pastoralnotable_id');
            $table->date('pastoraldate');
            $table->integer('pastor_id');
            $table->string('pastoralnotable_type', 199);
            $table->text('details');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pastoralnotes');
    }
};
