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
        Schema::create('expenses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('category');
            $table->float('amount');
            $table->text('description')->nullable();
            $table->timestamp('expense_date');
            $table->enum('expense_type', ['RECURRING', 'TEMPORARY'])->default('TEMPORARY');
            $table->uuid('chick_purchase_id')->nullable();
            $table->uuid('feed_id')->nullable();
            $table->uuid('medicine_id')->nullable();
            $table->uuid('equipment_id')->nullable();
            $table->uuid('branch_id');
            $table->timestamps();

            $table->foreign('chick_purchase_id')->references('id')->on('chick_purchases')->onDelete('set null');
            $table->foreign('feed_id')->references('id')->on('feeds')->onDelete('set null');
            $table->foreign('medicine_id')->references('id')->on('medicines')->onDelete('set null');
            $table->foreign('equipment_id')->references('id')->on('equipments')->onDelete('set null');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
