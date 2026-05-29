<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('containers', function (Blueprint $table) {
            $table->id();
            $table->string('kode_container')->unique();
            $table->string('jenis_limbah');
            $table->integer('kapasitas');
            $table->string('lokasi');
            $table->enum('status', ['Active', 'Maintenance', 'Full', 'Archived'])->default('Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('containers');
    }
};
