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
        Schema::table('lost_items', function (Blueprint $table) {
            $table->foreignId('resort_id')->nullable()->constrained('resorts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lost_items', function (Blueprint $table) {
            $table->dropForeign('lost_items_resort_id_foreign');
            $table->dropColumn('resort_id');
        });
    }
};
