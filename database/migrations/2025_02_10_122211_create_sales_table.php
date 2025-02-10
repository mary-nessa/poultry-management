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
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('product_type', ['EGG', 'HEN', 'POULTRY_PRODUCT']);
            $table->integer('quantity');
            $table->float('price_per_unit');
            $table->float('total_amount');
            $table->timestamp('sale_date');
            $table->uuid('branch_id');
            $table->uuid('buyer_id')->nullable();
            $table->uuid('egg_tray_id')->nullable();
            $table->uuid('product_id')->nullable();
            $table->enum('payment_method', ['CASH', 'CARD', 'MOBILE', 'CREDIT'])->nullable();
            $table->boolean('is_paid')->default(true);
            $table->float('balance')->nullable();
            $table->timestamps();

            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
            $table->foreign('buyer_id')->references('id')->on('buyers')->onDelete('set null');
            $table->foreign('egg_tray_id')->references('id')->on('egg_trays')->onDelete('set null');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
