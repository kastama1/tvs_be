<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type');
            $table->text('info');
            $table->timestamp('publish_from');
            $table->timestamp('start_from');
            $table->timestamp('end_to');
            $table->timestamps();
        });
    }
};
