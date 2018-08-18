<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOcupationAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ocupation_areas', function (Blueprint $table) {
            
            $table->integer('areaId')->unsigned();
            $table->foreign('areaId')->references('id')->on('areas');
            
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
        Schema::dropIfExists('ocupation_areas');
    }
}
