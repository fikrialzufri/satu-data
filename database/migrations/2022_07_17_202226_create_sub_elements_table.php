<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubElementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_element', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode');
            $table->string('nama');
            $table->string('slug');
            $table->float('nilai', 20, 3)->nullable();
            $table->longText('keterangan')->nullable();;
            $table->longText('sumber_data')->nullable();;
            $table->longText('metode_perhitungan')->nullable();
            $table->year('tahun')->nullable();
            $table->longText('meta_data')->nullable();;
            $table->string('satuan_id')->references('id')->on('satuan')->onDelete('cascade');
            $table->string('element_id')->references('id')->on('element')->onDelete('cascade');
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
        Schema::dropIfExists('sub_elements');
    }
}
