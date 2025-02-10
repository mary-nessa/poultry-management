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
        Schema::create('egg_trays', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('tray_type', ['SINGLE', 'HALF_TRAY', 'FULL_TRAY']);
            $table->integer('total_eggs');
            $table->integer('damaged_eggs')->default(0);
            $table->timestamp('collected_at');
            $table->enum('status', ['COLLECTED', 'STORED', 'SOLD', 'DAMAGED'])->default('COLLECTED');
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
        Schema::dropIfExists('egg_trays');
    }
};
