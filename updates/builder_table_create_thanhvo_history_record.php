<?php namespace Legato\History\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class BuilderTableCreateLegatoHistoryRecord extends Migration
{
    public function up()
    {
        Schema::create('thanhvo_history_record', function($table)
        {
            $table->increments('id')->unsigned();
            $table->string('entity', 40);
            $table->integer('entity_id')->unsigned();
            $table->integer('revision')->unsigned();
            $table->text('data')->nullable();
            $table->integer('user_id')->unsigned();
            $table->string('user_first_name', 255);
            $table->string('user_last_name', 255);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('thanhvo_history_record');
    }
}
