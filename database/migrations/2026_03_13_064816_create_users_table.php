<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('username');
            $table->string('password');

            $table->foreignId('role_id')
                ->constrained('roles', 'role_id')
                ->cascadeOnDelete();

            $table->timestamp('last_login')->nullable();
            $table->char('status', 1)->nullable();

            $table->unsignedBigInteger('owner_id')->nullable();
            $table->string('owner_type')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};