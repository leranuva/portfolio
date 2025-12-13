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
        Schema::create('portfolio_configs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role');
            $table->text('summary');
            $table->text('values_style')->nullable();
            $table->string('email');
            $table->string('linkedin_url')->nullable();
            $table->string('github_url')->nullable();
            $table->string('profile_image')->nullable();
            $table->boolean('dark_mode_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_configs');
    }
};
