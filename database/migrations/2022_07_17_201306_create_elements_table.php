<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('element', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode');
            $table->string('slug');
            $table->string('nama');
            $table->longText('keterangan')->nullable();
            $table->longText('dokumentasi')->nullable();
            $table->enum('setuju', ['Y', 'N'])->default('N');
            $table->string('group_id')->references('id')->on('group')->onDelete('cascade');
            $table->string('unit_id')->references('id')->on('unit')->onDelete('cascade');
            $table->string('jenis_data_id')->references('id')->on('jenis_data')->onDelete('cascade');
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
        Schema::dropIfExists('elements');
    }
}
