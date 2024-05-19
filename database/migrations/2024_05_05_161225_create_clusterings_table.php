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
        Schema::create('clusterings', function (Blueprint $table) {
            $table->id();
            $table->decimal('hluaspanen');
            $table->decimal('hproduktivitas');
            $table->decimal('hproduksi');
            $table->smallInteger('htahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clusterings');
    }
};
