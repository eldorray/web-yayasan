<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('level', 8); // TK, SD, SMP, SMA, SMK
            $table->text('description')->nullable();
            $table->string('address')->nullable();
            $table->decimal('map_lat', 10, 7)->nullable();
            $table->decimal('map_lng', 10, 7)->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('website_url')->nullable();
            $table->year('established_year')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
