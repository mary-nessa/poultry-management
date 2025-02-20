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
        Schema::create('health_checks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('breed');
            $table->integer('alive_chicks');
            $table->integer('dead_chicks');
            $table->integer('alive_hens');
            $table->integer('dead_hens');
            $table->integer('sick_hens');
            $table->integer('sick_chicks');
            $table->timestamps('check_date');
            $table->text('health_notes');
            $table->uuid('worker_id');
            $table->uuid('branch_id');
            $table->timestamps();

            $table->foreign('worker_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_checks');
    }
};
