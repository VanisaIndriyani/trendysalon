<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hair_vitamins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hair_type'); // Sehat, Kering, Rusak, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hair_vitamins');
    }
};