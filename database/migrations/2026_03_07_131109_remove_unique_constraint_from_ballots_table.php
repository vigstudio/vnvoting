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
        Schema::table('ballots', function (Blueprint $table) {
            // MySQL requires dropping foreign keys before dropping a unique index that includes them
            $table->dropForeign(['election_id']);
            $table->dropForeign(['position_id']);

            $table->dropUnique(['election_id', 'position_id']);

            // Re-add foreign keys
            $table->foreign('election_id')->references('id')->on('elections')->cascadeOnDelete();
            $table->foreign('position_id')->references('id')->on('positions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ballots', function (Blueprint $table) {
            $table->dropForeign(['election_id']);
            $table->dropForeign(['position_id']);

            $table->unique(['election_id', 'position_id']);

            $table->foreign('election_id')->references('id')->on('elections')->cascadeOnDelete();
            $table->foreign('position_id')->references('id')->on('positions')->cascadeOnDelete();
        });
    }
};
