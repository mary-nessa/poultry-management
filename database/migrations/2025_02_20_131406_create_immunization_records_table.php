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
        Schema::create('immunization_records', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('chick_purchase_id')->nullable();
            $table->uuid('vaccine_id')->nullable();
            $table->date('immunization_date');
            $table->date('next_due_date');
            $table->text('notes')->nullable();
            $table->integer('number_immunized');
            $table->string('age_category');
            $table->timestamps();

            $table->foreign('chick_purchase_id')->references('id')->on('chick_purchases')->onDelete('set null');
            $table->foreign('vaccine_id')->references('id')->on('medicines')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('immunization_records');
    }
};
