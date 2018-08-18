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

            $table->string('idRgistration')->unique();
            $table->string('name', 50);
            $table->string('cpf', 11)->unique();
            $table->string('email', 50)->unique();
            $table->enum('type', ['ADM', 'TEATCHER']);
            $table->integer('studentLimit');

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
