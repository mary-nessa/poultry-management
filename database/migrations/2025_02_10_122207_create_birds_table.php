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
        Schema::create('birds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->integer('hen_count');
            $table->integer('cock_count');
            $table->float('mortality_rate')->nullable();
            $table->uuid('branch_id');
            $table->uuid('batch_id')->nullable()->after('id');
            $table->foreign('batch_id')->references('id')->on('bird_batches')->onDelete('set null');

            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('birds');
    }
};
