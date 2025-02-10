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
        Schema::create('daily_activities', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('worker_id');
            $table->uuid('branch_id');
            $table->timestamp('activity_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->text('feeding_notes')->nullable();
            $table->text('health_notes')->nullable();
            $table->integer('egg_collection_count')->nullable();
            $table->integer('damaged_egg_count')->nullable();
            $table->integer('egg_sales_count')->nullable();
            $table->float('hen_sale_expenses')->nullable();
            $table->float('feed_consumed_kg')->nullable();
            $table->uuid('egg_tray_id')->nullable();
            $table->timestamps();

            $table->foreign('worker_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('egg_tray_id')->references('id')->on('egg_trays')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_activities');
    }
};
