<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCkanVisitorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ckan_visitors', function (Blueprint $table) {
            $table->id();
            $table->string('iid')->nullable()->index();
            $table->string('url')->nullable();
            $table->unsignedBigInteger('count')->default(0);
            $table->enum('period', ['daily', 'monthly', 'yearly']);
            $table->date('period_date')->index();
            $table->timestamps();

            $table->unique(['period', 'period_date', 'iid']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ckan_visitors');
    }
}
