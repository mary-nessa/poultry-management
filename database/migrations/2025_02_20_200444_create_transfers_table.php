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
        Schema::create('transfers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->foreignId('breed_id')->nullable();
            $table->foreign('breed_id')->references('id')->on('breeds')->onDelete('cascade');
            $table->string('status')->default('pending');
            $table->foreignUuid('user_id')->constrained('users');
            $table->foreignUuid('from_branch_id')->constrained('branches');
            $table->foreignUuid('to_branch_id')->constrained('branches');
            $table->integer('quantity');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
