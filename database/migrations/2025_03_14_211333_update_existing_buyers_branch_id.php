<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Branch;
use App\Models\Buyer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $firstBranch = Branch::first();
        if ($firstBranch) {
            Buyer::whereNull('branch_id')->update(['branch_id' => $firstBranch->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Buyer::query()->update(['branch_id' => null]);
    }
};
