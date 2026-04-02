<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parkings', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code')->unique();       // Kode unik karcis
            $table->string('no_kendaraan');               // Nomor plat kendaraan
            $table->enum('jenis_kendaraan', ['motor', 'mobil', 'truk'])->default('motor');
            $table->timestamp('waktu_masuk');
            $table->timestamp('waktu_keluar')->nullable();
            $table->enum('status', ['parkir', 'keluar'])->default('parkir');
            $table->decimal('tarif', 10, 0)->nullable();  // Total bayar
            $table->foreignId('petugas_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parkings');
    }
};