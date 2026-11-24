<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_images', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image_path');
            $table->unsignedBigInteger('masjid_id')->nullable();
            $table->string('city')->nullable();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('likes')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_trending')->default(false);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('uploaded_by')->nullable();
            $table->timestamps();

            $table->foreign('masjid_id')->references('id')->on('masjids')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gallery_images');
    }
};
