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
        Schema::create('bird_transfers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bird_id');
            $table->uuid('from_branch_id');
            $table->uuid('to_branch_id');
            $table->integer('quantity');
            $table->timestamp('transfer_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('bird_id')->references('id')->on('birds')->onDelete('cascade');
            $table->foreign('from_branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('to_branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bird_transfers');
    }
};
