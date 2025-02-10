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
            $table->integer('chick_count');
            $table->timestamp('egg_laid_date')->nullable();
            $table->timestamp('hatch_date')->nullable();
            $table->float('mortality_rate')->nullable();
            $table->float('purchase_cost')->nullable();
            $table->timestamp('acquisition_date')->nullable();
            $table->uuid('branch_id');
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
