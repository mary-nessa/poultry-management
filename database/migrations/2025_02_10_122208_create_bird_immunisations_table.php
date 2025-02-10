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
        Schema::create('bird_immunisations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bird_id');
            $table->string('vaccine_name');
            $table->timestamp('immunisation_date');
            $table->timestamp('next_due_date')->nullable();
            $table->text('notes')->nullable();
            $table->integer('number_immunized');
            $table->enum('age_category', ['CHICK', 'PULLET', 'ADULT']);
            $table->timestamps();

            $table->foreign('bird_id')->references('id')->on('birds')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bird_immunisations');
    }
};
