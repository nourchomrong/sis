<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
           Schema::create('teachers', function (Blueprint $table) {
        $table->id('teacher_id');
        $table->integer('teacher_code');
        $table->string('en_fullname');
        $table->string('kh_fullname')->nullable();
        $table->char('gender', 1);
        $table->date('dateofbirth');
        $table->string('birthplace')->nullable();
        $table->text('address')->nullable();
        $table->string('phone')->nullable();
        $table->string('email')->nullable();
        $table->char('status', 1)->default('A');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
