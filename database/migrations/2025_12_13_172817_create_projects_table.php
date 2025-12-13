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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->text('problem_solution');
            $table->text('role');
            $table->json('technologies'); // Frontend, Backend, DB, etc.
            $table->string('demo_url')->nullable();
            $table->string('repository_url')->nullable();
            $table->string('live_url')->nullable();
            $table->text('results_learnings')->nullable();
            $table->string('image')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
