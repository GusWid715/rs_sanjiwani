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
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('aktivitas', 255)->nullable();
            $table->string('entity', 50)->nullable();
            $table->unsignedInteger('entity_id')->nullable();
            $table->timestamp('waktu')->useCurrent();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
