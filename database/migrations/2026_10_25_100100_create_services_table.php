<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('group')->default('systems');
            $table->string('title_en');
            $table->string('title_ar');
            $table->string('excerpt_en', 500)->nullable();
            $table->string('excerpt_ar', 500)->nullable();
            $table->text('body_en')->nullable();
            $table->text('body_ar')->nullable();
            $table->text('features_en')->nullable();
            $table->text('features_ar')->nullable();
            $table->string('icon')->default('layers');
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
