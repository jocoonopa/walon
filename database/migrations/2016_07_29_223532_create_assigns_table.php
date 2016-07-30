<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assigns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('count')->unsigned();
            $table->string('good_choices');
            $table->string('bad_choices');
            $table->string('places');
            $table->timestamps();
        });

        Schema::table('games', function (Blueprint $table) {
            $table->integer('assign_id')->unsigned()->index()->nullable();

            $table
                ->foreign('assign_id')
                ->references('id')
                ->on('assigns')
                ->onDelete('cascade')
            ;
        });
    }   

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('assigns');
    }
}
