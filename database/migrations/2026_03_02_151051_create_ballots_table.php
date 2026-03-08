<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ballots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->cascadeOnDelete();
            $table->foreignId('position_id')->constrained()->cascadeOnDelete();
            $table->integer('expected_count')->default(0);
            $table->integer('entered_count')->default(0);
            $table->timestamp('counted_at')->nullable();
            $table->timestamps();

            $table->unique(['election_id', 'position_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ballots');
    }
};
