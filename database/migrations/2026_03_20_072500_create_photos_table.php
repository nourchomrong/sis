<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('photos', function (Blueprint $table) {
        $table->id('photo_id');

        // polymorphic relation
        $table->unsignedBigInteger('owner_id');
        $table->string('owner_type');

        $table->string('photo_path'); // use string instead of binary
        $table->char('status', 1)->default('A');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
