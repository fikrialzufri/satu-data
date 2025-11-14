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
        Schema::table('sub_element', function (Blueprint $table) {
            if (!Schema::hasColumn('sub_element', 'lokasi_data')) {
                $table->json('lokasi_data')->nullable()->after('meta_data');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sub_element', function (Blueprint $table) {
            if (Schema::hasColumn('sub_element', 'lokasi_data')) {
                $table->dropColumn('lokasi_data');
            }
        });
    }
};

