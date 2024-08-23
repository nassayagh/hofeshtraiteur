<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('prestations', function (Blueprint $table) {
            $table->dateTimeTz("validated_date")->nullable();
            $table->dateTimeTz("start_date")->nullable();
            $table->dateTimeTz("closed_date")->nullable();
            $table->dateTimeTz("cancelled_date")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('prestations', function (Blueprint $table) {
            //
        });
    }
};
