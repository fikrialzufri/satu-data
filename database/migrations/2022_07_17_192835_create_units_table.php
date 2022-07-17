<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('unit', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->string('slug');
            $table->string('nama_singkat');
            $table->string('latitude');
            $table->string('longitude');
            $table->string('logo');
            $table->string('email');
            $table->text('alamat')->nullable();
            $table->longText('keterangan')->nullable();
            $table->string('jenis_unit_id')->references('id')->on('jenis_unit')->onDelete('cascade');
            $table->enum('tampil', ['Y', 'N'])->default('Y');
            $table->enum('akun', ['Y', 'N'])->default('Y');
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
        Schema::dropIfExists('units');
    }
}
