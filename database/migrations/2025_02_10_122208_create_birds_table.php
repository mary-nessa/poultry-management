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
            $table->uuid('chick_purchase_id');
            $table->integer('hen_count');
            $table->integer('cock_count');
            $table->integer('total_birds');
            $table->date('laying_cycle_start_date')->nullable();
            $table->date('laying_cycle_end_date')->nullable();

            $table->timestamps();

            $table->foreign('chick_purchase_id')->references('id')->on('chick_purchases')->onDelete('set null');
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
