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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->integer('width');
            $table->integer('height');
            $table->integer('score');
            $table->integer('fruit_x')->nullable();
            $table->integer('fruit_y')->nullable();
            $table->integer('snake_x');
            $table->integer('snake_y');
            $table->integer('snake_vel_x');
            $table->integer('snake_vel_y');
            $table->boolean('is_over')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
