<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('egg_collections', function (Blueprint $table) {
            $table->timestamp('collection_date')->nullable()->after('branch_id');
        });

        // Set existing records' collection_date to their created_at timestamp
        DB::table('egg_collections')->whereNull('collection_date')->update([
            'collection_date' => DB::raw('created_at')
        ]);
    }

    public function down()
    {
        Schema::table('egg_collections', function (Blueprint $table) {
            $table->dropColumn('collection_date');
        });
    }
};