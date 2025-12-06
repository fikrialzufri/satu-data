<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddViewerToInfografikTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('infografik', function (Blueprint $table) {
            $table->unsignedBigInteger('viewer')->default(0)->after('isi_infografik');
        });
    }

    public function down(): void
    {
        Schema::table('infografik', function (Blueprint $table) {
            $table->dropColumn('viewer');
        });
    }
}
