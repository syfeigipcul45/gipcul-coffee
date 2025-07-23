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
        Schema::create('bahan_pokoks', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bahan');
            $table->integer('jumlah_berat')->nullable();
            $table->unsignedBigInteger('satuan_id');
            $table->string('harga')->nullable();
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
