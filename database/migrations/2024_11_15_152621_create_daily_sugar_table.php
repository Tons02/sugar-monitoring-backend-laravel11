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
        Schema::create('daily_sugar', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger(column: "user_id")->index();
            $table->double('mgdl');
            $table->string('description');
            $table->string('status');
            $table->dateTime('date');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("user_id")
            ->references("id")
            ->on("users")
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_sugar');
    }
};
