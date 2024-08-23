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
        Schema::create('demands', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id')->nullable()->default(0);
            $table->string('service')->nullable();
            $table->string('event_type')->nullable();
            $table->string('event_location')->nullable();
            $table->string('reception_start_time')->nullable();
            $table->integer('number_people')->nullable();
            $table->longText('comment')->nullable();
            $table->dateTimeTz('event_date')->nullable();
            $table->dateTimeTz('demand_date')->nullable();
            $table->integer('status')->nullable()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demands');
    }
};
