<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pastorables', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('pastor_id');
            $table->integer('pastorable_id');
            $table->string('pastorable_type', 199);
            $table->string('details', 199)->nullable();
            $table->boolean('actiive');
            $table->boolean('prayerlist');
            $table->string('prayernote', 199)->nullable();
            $table->renameColumn('actiive', 'active');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('pastorables');
    }
};
