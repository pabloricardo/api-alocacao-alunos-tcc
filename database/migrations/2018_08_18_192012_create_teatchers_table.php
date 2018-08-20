<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeatchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teatchers', function (Blueprint $table) {
            $table->increments('id');

            $table->enum('type', ['ADM', 'TEATCHER']);
            $table->integer('studentLimit');

            $table->integer('userId')->unsigned();
            $table->foreign('userId')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teatchers');
    }
}
