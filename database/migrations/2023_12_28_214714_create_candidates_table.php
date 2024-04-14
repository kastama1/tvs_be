<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('campaign');
            $table->timestamps();
            $table->foreignId('election_party_id')->nullable()->constrained()->cascadeOnDelete();
        });
    }
};
