<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('individuals', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->string('title', 255)->nullable();
            $table->string('surname', 255);
            $table->string('firstname', 255);
            $table->string('email', 255)->nullable();
            $table->string('birthdate', 255)->nullable();
            $table->string('sex', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('memberstatus', 255)->nullable();
            $table->integer('household_id');
            $table->string('giving', 255)->nullable();
            $table->string('officephone', 255)->nullable();
            $table->string('cellphone', 255)->nullable();
            $table->text('notes')->nullable();
            $table->string('welcome_email', 255)->nullable();
            $table->integer('user_id')->nullable();
            $table->string('uid', 255)->nullable();
            $table->json('app', 255)->nullable();
            $table->tinyInteger('online')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('individuals');
    }
};
