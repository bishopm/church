<?php namespace Bishopm\Church\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateBishopmChurchHouseholds extends Migration
{
    public function up()
    {
        Schema::create('bishopm_church_households', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('addressee', 255);
            $table->string('address1', 255)->nullable();
            $table->string('address2', 255)->nullable();
            $table->string('address3', 255)->nullable();
            $table->string('homephone', 255)->nullable();
            $table->integer('householdcell')->nullable();
            $table->decimal('latitude', 20, 15)->nullable();
            $table->decimal('longitude', 20, 15)->nullable();
            $table->string('sortsurname', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bishopm_church_households');
    }
}
