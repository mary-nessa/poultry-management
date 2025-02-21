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
        Schema::create('egg_collections', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('total_eggs');
            $table->integer('good_eggs');
            $table->integer('damaged_eggs');
            $table->integer('broken_eggs');
            $table->integer('full_trays');
            $table->integer('1_2_trays');
            $table->integer('single_eggs');
            $table->uuid('collected_by');
            $table->uuid('branch_id');
            $table->timestamps();

            $table->foreign('collected_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egg_collections');
    }
};
