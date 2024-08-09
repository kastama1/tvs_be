<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->blob('votable_id');
            $table->string('votable_type');
            $table->string('hash');
            $table->string('previous_hash');
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('election_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vote_id')->nullable()->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }
};
