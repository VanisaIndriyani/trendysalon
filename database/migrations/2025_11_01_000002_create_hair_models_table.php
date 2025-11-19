<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hair_models', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image')->nullable(); // path ke public/img
            $table->string('types')->nullable(); // contoh: "Lurus, Ikal, Bergelombang"
            $table->string('length')->nullable(); // Panjang/Pendek
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hair_models');
    }
};