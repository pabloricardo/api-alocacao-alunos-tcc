<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificableTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificable_teachers', function (Blueprint $table) {
            $table->increments('id');

            $table->enum('teacherGuide', ['YES', 'NO'])->default('NO');
            $table->enum('answered', ['YES', 'NO'])->default('NO');

            $table->integer('teacherId')->unsigned();
            $table->foreign('teacherId')->references('id')->on('teachers');

            $table->integer('studentId')->unsigned();
            $table->foreign('studentId')->references('id')->on('students');

            $table->integer('areaId')->unsigned();
            $table->foreign('areaId')->references('id')->on('areas');

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
        Schema::dropIfExists('notificable_teachers');
    }
}
