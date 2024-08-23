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
        Schema::create('prestation_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prestation_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('quantity')->nullable()->default(0);
            $table->double('price',2)->nullable()->default(0);
            $table->double('total',2)->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestation_services');
    }
};
