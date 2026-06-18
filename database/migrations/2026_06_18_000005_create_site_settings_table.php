<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name')->default('Yayasan Pendidikan Daarul Hikmah Al Madani');
            $table->string('tagline')->nullable();
            $table->text('vision')->nullable();
            $table->text('mission')->nullable();
            $table->text('history')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->year('established_year')->nullable();
            $table->integer('students_count')->nullable();
            $table->json('socials')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
