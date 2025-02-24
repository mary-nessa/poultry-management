<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Add the foreign key to the feeds table
        Schema::table('feeds', function (Blueprint $table) {
            $table->unsignedBigInteger('feed_type_id'); // Add the foreign key column
            $table->foreign('feed_type_id')->references('id')->on('feed_types')->onDelete('cascade'); // Set the foreign key relationship
        });
    }

    public function down()
    {
        // Drop the foreign key and the column
        Schema::table('feeds', function (Blueprint $table) {
            $table->dropForeign(['feed_type_id']);
            $table->dropColumn('feed_type_id');
        });
    }
};
