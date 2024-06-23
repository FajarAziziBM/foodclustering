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
        Schema::create('hasil_clusters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clusterId')->constrained('clusterings')->onUpdate('cascade')->onDelete('restrict');
            $table->string('cluster', 15);
            $table->text('anggota_cluster');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hasil_clusters');
    }
};
