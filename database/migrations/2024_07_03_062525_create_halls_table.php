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
        Schema::create('halls', function (Blueprint $table) {
            $table->id();
            $table->string("name")->nullable();
            $table->string("address")->nullable();
            $table->string("manager_name")->nullable();
            $table->string("manager_phone")->nullable();
            $table->boolean("packing")->default(false)->nullable();
            $table->boolean("kitchen")->default(false)->nullable();
            $table->boolean("cold_room")->default(false)->nullable();
            $table->boolean("ladder")->default(false)->nullable();
            $table->boolean("transportation_fee")->default(false)->nullable();
            $table->boolean("table")->default(false)->nullable();
            $table->string("bin")->default('client')->nullable();
            $table->longText("comment")->nullable();
            $table->integer("user_id")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('halls');
    }
};
