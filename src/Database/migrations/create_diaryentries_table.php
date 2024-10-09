<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('diaryentries', function ($table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->unsigned();
            $table->integer('diarisable_id')->default(null);
            $table->string('diarisable_type', 191);
            $table->dateTime('diarydatetime');
            $table->string('details', 191)->default(null);
            $table->integer('venue_id')->default(null);
            $table->time('endtime');
            $table->timestamp('created_at')->nullable()->default(null);
            $table->timestamp('updated_at')->nullable()->default(null);
            $table->tinyInteger('calendar')->nullable()->default(null);
            $table->tinyInteger('publicinvite')->nullable()->default(null);
            $table->tinyInteger('agenda')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('diaryentries');
    }
};
