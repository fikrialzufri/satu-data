<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode');
            $table->string('slug');
            $table->string('nama');
            $table->longText('keterangan')->nullable();
            $table->longText('dokumentasi')->nullable();
            $table->enum('setuju', ['Y', 'N'])->default('N');
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
        Schema::dropIfExists('groups');
    }
}
