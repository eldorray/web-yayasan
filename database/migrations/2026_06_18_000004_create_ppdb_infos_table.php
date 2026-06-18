<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ppdb_infos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->string('academic_year', 12); // mis. 2026/2027
            $table->date('open_date')->nullable();
            $table->date('close_date')->nullable();
            $table->text('requirements')->nullable();
            $table->string('fees')->nullable();
            $table->string('registration_url')->nullable();
            $table->boolean('is_open')->default(true);
            $table->timestamps();
            $table->unique(['school_id', 'academic_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_infos');
    }
};
