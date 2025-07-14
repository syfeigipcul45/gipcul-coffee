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
        Schema::create('detail_pembelians', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pembelian_id');
            $table->unsignedBigInteger('bahan_id');
            $table->integer('jumlah_berat');
            $table->unsignedBigInteger('satuan_id');
            $table->integer('harga');
            $table->foreign('pembelian_id')->references('id')->on('pembelian_bahans')->onDelete('cascade');
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
        //
    }
};
