<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('set_id')
                ->constrained('sets')
                ->onDelete('cascade');
            $table->string('nama_menu');
            $table->text('deskripsi')->nullable();
            $table->integer('stok')->default(0);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('menus');
    }
};
