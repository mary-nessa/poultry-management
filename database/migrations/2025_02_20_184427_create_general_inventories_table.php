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
        Schema::create('general_inventories', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('branch_id')->constrained()->cascadeOnDelete();
            $table->string('breed');
            $table->integer('total_eggs')->default(0);
            $table->integer('total_chicks')->default(0);
            $table->integer('total_cocks')->default(0);
            $table->integer('total_hens')->default(0);
            $table->foreignUuid('created_by')->nullable()->constrained('users')->onDelete('SET NULL');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_inventories');
    }
};
