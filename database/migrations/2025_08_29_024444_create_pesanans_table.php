<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade');
            $table->foreignId('menu_id')
                ->constrained('menus')
                ->onDelete('cascade');
            $table->integer('jumlah')->default(1);
            $table->date('tanggal')->nullable();
            $table->string('status')->default('pending');
            $table->string('ruangan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pesanans');
    }
};
