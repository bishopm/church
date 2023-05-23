<?php namespace Bishopm\Church\Updates;

use Schema;
use Winter\Storm\Database\Updates\Migration;

class BuilderTableCreateBishopmChurchIndividuals extends Migration
{
    public function up()
    {
        Schema::create('bishopm_church_individuals', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('title', 255)->nullable();
            $table->string('surname', 255);
            $table->string('firstname', 255);
            $table->string('sex', 255)->nullable();
            $table->string('memberstatus', 255);
            $table->integer('household_id');
            $table->string('email', 255)->nullable();
            $table->string('cellphone', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->text('notes')->nullable();
            $table->string('officephone', 255)->nullable();
            $table->string('giving', 255)->nullable();
            $table->string('birthdate', 255)->nullable();
            $table->integer('user_id')->nullable();
            $table->string('uid', 255)->nullable();
            $table->string('welcome_email', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('bishopm_church_individuals');
    }
}
