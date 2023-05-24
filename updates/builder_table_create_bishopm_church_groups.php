<?php namespace Bishopm\Church\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateBishopmChurchGroups extends Migration
{
    public function up()
    {
        Schema::create('bishopm_church_groups', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('groupname', 255);
            $table->text('description')->nullable();
            $table->integer('individual_id')->nullable();
            $table->string('grouptype', 255);
            $table->boolean('publish')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bishopm_church_groups');
    }
}
