<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGalleryInfografikTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('gallery_infografik', function (Blueprint $table) {
            $table->uuid('gallery_id');
            $table->uuid('infografik_id');
            $table->timestamps();

            $table->primary(['gallery_id', 'infografik_id']);
            $table->foreign('gallery_id')->references('id')->on('galleries')->onDelete('cascade');
            $table->foreign('infografik_id')->references('id')->on('infografik')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_infografik');
    }
}
