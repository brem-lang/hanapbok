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
        Schema::create('entrance_fees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resort_id')->constrained('resorts')->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->double('price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entrance_fees');
    }
};
