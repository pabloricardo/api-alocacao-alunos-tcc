<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('students', function (Blueprint $table) {
            $table->increments('id');
            
            $table->string('idRgistration')->unique();
            $table->string('name', 50);
            $table->string('cpf', 11)->unique();
            $table->string('email', 50)->unique();
            $table->string('curse', 30);

            $table->integer('teacthcerId')->unsigned();
            $table->foreign('teacthcerId')->references('id')->on('teatchers');

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
        Schema::dropIfExists('students');
    }
}
