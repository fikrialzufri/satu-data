<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInfografikTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('infografik', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->uuid('kategori_infografik_id')->nullable();
            $table->uuid('thumbnail_id')->nullable();
            $table->text('isi_infografik')->nullable();
            $table->timestamps();

            $table->foreign('kategori_infografik_id')->references('id')->on('kategori_infografik')->onDelete('set null');
            $table->foreign('thumbnail_id')->references('id')->on('galleries')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('infografik');
    }
}
