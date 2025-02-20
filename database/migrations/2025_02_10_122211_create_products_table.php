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
            Schema::create('products', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('branch_id');
                $table->string('product_type');
                $table->string('breed');
                $table->string('unit_measure');
                $table->float('default_price');
                $table->timestamps();

                $table->unique('product_type');
                $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
