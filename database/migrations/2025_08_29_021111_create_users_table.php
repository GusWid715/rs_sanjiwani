<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name', 100);
            $table->string('password');
            $table->enum('role', ['pasien', 'manager'])->default('pasien'); 
            $table->rememberToken(); // Kolom remember_token VARCHAR(100) NULL
        });
    }

    public function down(): void {
        Schema::dropIfExists('users');
    }
};
