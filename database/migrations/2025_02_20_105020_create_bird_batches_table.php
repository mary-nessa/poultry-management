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
        Schema::create('bird_batches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('branch_id');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');

            // New: Reference to the supplier
            $table->uuid('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('set null');

            $table->enum('purchase_method', ['egg', 'chick', 'adult']);
            $table->integer('purchased_quantity');
            $table->integer('unknown_gender')->nullable();
            $table->integer('hen_count')->nullable();
            $table->integer('cock_count')->nullable();
            $table->date('egg_laid_date')->nullable();
            $table->date('hatch_date')->nullable();
            $table->integer('actual_hatched')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->date('acquisition_date')->nullable();
            $table->enum('status', ['pending', 'hatched', 'completed'])->default('pending');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bird_batches');
    }
};
