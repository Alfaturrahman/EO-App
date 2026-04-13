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
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('paket_id')->nullable()->constrained()->onDelete('set null');
            $table->string('nama_acara');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->text('alamat_acara');
            $table->decimal('total_harga', 15, 2);
            $table->enum('status_pembayaran', ['pending', 'lunas'])->default('pending');
            $table->enum('status_pengambilan', ['belum_diambil', 'dalam_penggunaan', 'selesai'])->default('belum_diambil');
            $table->string('bukti_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};
