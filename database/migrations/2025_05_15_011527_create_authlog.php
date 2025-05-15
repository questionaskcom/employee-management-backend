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
        Schema::create('authlog', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('email')->nullable();
            $table->string('method')->nullable();
            $table->string('ip_address')->nullable();
            $table->json('location')->nullable();
            $table->string('action')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('authlog');
    }
};
