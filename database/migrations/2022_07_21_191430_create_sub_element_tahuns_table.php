<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubElementTahunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_element_tahun', function (Blueprint $table) {
            $table->id();
            $table->float('nilai', 20, 3)->nullable();
            $table->year('tahun')->nullable();
            $table->string('sub_element_id')->references('id')->on('sub_element')->onDelete('cascade');
            $table->string('legenda_id')->references('id')->on('legenda')->onDelete('cascade');
            $table->string('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('sub_element_tahuns');
    }
}
