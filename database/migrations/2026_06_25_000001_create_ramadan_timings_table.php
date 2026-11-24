<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ramadan_timings', function (Blueprint $table) {
            $table->id();
            $table->string('city')->default('Karachi');
            $table->integer('year')->default(now()->year);
            $table->integer('day');
            $table->string('sehri_time')->nullable();
            $table->string('iftar_time')->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ramadan_timings');
    }
};
