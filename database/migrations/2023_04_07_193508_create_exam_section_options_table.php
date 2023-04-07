<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exam_section_options', function (Blueprint $table) {
            $table->id();
            $table->string('option');
            $table->unsignedBigInteger('exam_section_id');
            $table->foreign('exam_section_id')->references('id')->on('exam_sections');
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
        Schema::dropIfExists('exam_section_options');
    }
};
