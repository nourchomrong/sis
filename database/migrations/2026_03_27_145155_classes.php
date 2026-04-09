<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('classes', function (Blueprint $table) {

            $table->id('class_id');

            $table->string('class_name');
            $table->string('grade_level');

            $table->foreignId('year_id')->nullable()->constrained('years', 'year_id') ->nullOnDelete();
            $table->foreignId('classroom_id')->constrained('classrooms', 'classroom_id') ->cascadeOnDelete();

            $table->foreignId('teacher_id')->nullable()->constrained('teachers', 'teacher_id') ->nullOnDelete();
            $table->char('status', 1)->default('A');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classes');
    }
};
