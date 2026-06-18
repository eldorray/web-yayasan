<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt')->nullable();
            $table->longText('body')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('category', 16)->default('yayasan'); // yayasan | sekolah
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->boolean('is_published')->default(false);
            $table->timestamps();
            $table->index(['is_published', 'published_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
