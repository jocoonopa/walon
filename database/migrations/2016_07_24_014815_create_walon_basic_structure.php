<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalonBasicStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table){
            $table->increments('id');
            $table->string('location')->default('somewhere');
            $table->boolean('is_own_by_jus')->nullable();
            $table->timestamps();
        });

        Schema::create('characters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('des');
            $table->string('image')->nullable();
            $table->boolean('is_good');
        });

        Schema::create('election_player', function (Blueprint $table){
            $table->integer('election_id')->unsigned()->index()->nullable();
            $table->integer('player_id')->unsigned()->index()->nullable();

            $table
                ->foreign('election_id')
                ->references('id')
                ->on('elections')
                ->onDelete('cascade')
            ;

            $table
                ->foreign('player_id')
                ->references('id')
                ->on('players')
                ->onDelete('cascade')
            ;
        });
        
        Schema::create('players', function (Blueprint $table){
            $table->increments('id');

            $table->integer('game_id')->unsigned()->index();            
            $table
                ->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onDelete('cascade')
            ;

            $table->integer('character_id')->unsigned()->index();            
            $table
                ->foreign('character_id')
                ->references('id')
                ->on('characters')
                ->onDelete('cascade')
            ;

            $table->integer('user_id')->unsigned()->index();            
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
            ;

            $table
                ->foreign('id')
                ->references('player_id')
                ->on('election_player')
                ->onDelete('cascade')
            ;
        });
        
        Schema::create('missions', function (Blueprint $table){
            $table->increments('id');

            $table->integer('serno');
            $table->boolean('is_success')->nullable();

            $table->integer('game_id')->unsigned()->index();            
            $table
                ->foreign('game_id')
                ->references('id')
                ->on('games')
                ->onDelete('cascade')
            ;
        });
        
        Schema::create('elections', function (Blueprint $table){
            $table->increments('id');
            $table->integer('turn');
            $table->boolean('is_pass')->nullable();
            $table->integer('host_id')->unsigned()->index();            
            $table
                ->foreign('host_id')
                ->references('id')
                ->on('player')
                ->onDelete('cascade')
            ;

            $table->integer('mission_id')->unsigned()->index();            
            $table
                ->foreign('mission_id')
                ->references('id')
                ->on('mission')
                ->onDelete('cascade')
            ;

             $table
                ->foreign('id')
                ->references('election_id')
                ->on('election_player')
                ->onDelete('cascade')
            ;
        });
        
        Schema::create('votes', function (Blueprint $table){
            $table->increments('id');

            $table->integer('election_id')->unsigned()->index();            
            $table
                ->foreign('election_id')
                ->references('id')
                ->on('elections')
                ->onDelete('cascade')
            ;

            $table->integer('player_id')->unsigned()->index();            
            $table
                ->foreign('player_id')
                ->references('id')
                ->on('players')
                ->onDelete('cascade')
            ;

            $table->boolean('is_agree');
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('votes');
        Schema::drop('elections');
        Schema::drop('election_player');
        Schema::drop('players');
        Schema::drop('missions');
        Schema::drop('games');
        Schema::drop('characters');
    }
}
