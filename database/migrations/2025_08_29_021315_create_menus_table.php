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
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama_menu', 100);
            $table->unsignedInteger('kategori_id');
            $table->text('deskripsi')->nullable();
            $table->integer('stok')->default(0);
            $table->timestamps();

            $table->foreign('kategori_id')->references('id')->on('kategori_menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
