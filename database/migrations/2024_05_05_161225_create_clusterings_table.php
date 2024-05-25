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
            $table->foreignId('provinsiId')->constrained('provinces')->onUpdate('cascade')->onDelete('restrict');
            $table->double('eps');
            $table->double('minpts');
            $table->tinyInteger('jmlcluster');
            $table->tinyInteger('jmlnoice');
            $table->double('jmlhtercluster');
            $table->double('silhouetteindek');
            $table->string('detail');

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
