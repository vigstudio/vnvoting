<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('ballot_color')->default('#FFFFFF');
            $table->integer('max_votes')->default(1);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['election_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
