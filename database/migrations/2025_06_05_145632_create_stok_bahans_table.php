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
        Schema::create('stok_bahans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bahan_id');
            $table->integer('jumlah_berat');
            $table->unsignedBigInteger('satuan_id');
            $table->foreign('bahan_id')->references('id')->on('bahan_pokoks')->onDelete('cascade');
            $table->foreign('satuan_id')->references('id')->on('satuans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stok_bahans');
    }
};
