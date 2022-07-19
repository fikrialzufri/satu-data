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
            $table->string('lat_long');
            $table->string('logo')->nullable();
            $table->string('email')->nullable();
            $table->string('telepon', 13)->nullable();
            $table->text('alamat')->nullable();
            $table->text('detail_alamat')->nullable();
            $table->longText('keterangan')->nullable();
            $table->string('jenis_unit_id')->references('id')->on('jenis_unit')->onDelete('cascade');
            $table->enum('tampil', ['Y', 'N'])->default('Y');
            $table->enum('akun', ['Y', 'N'])->default('Y');
            $table->enum('setuju', ['Y', 'N'])->default('Y');
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
        Schema::dropIfExists('units');
    }
}
