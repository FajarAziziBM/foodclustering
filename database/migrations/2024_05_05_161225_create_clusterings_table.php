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
            $table->double('eps', 15);
            $table->double('minpts', 15);
            $table->integer('jmlcluster');
            $table->integer('jmlnoice');
            $table->integer('jmltercluster');
            $table->double('silhouette_index', 15);
            $table->integer('tahun');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clusterings');
    }

    // database/migrations/xxxx_xx_xx_create_province_labels_table.php
    // public function up()
    // {
    //     Schema::create('province_labels', function (Blueprint $table) {
    //         $table->id();
    //         $table->string('province');
    //         $table->integer('label');
    //         $table->timestamps();
    //     });
    // }

};
