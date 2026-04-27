<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tarif;

class Parking extends Model
{
    protected $fillable = [
        'ticket_code',
        'no_kendaraan',
        'jenis_kendaraan',
        'waktu_masuk',
        'waktu_keluar',
        'status',
        'tarif',
        'petugas_id',
    ];

    protected $casts = [
        'waktu_masuk'  => 'datetime',
        'waktu_keluar' => 'datetime',
    ];

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function getIsInapAttribute(): bool
    {
        if ($this->status !== 'parkir' || !$this->waktu_masuk) {
            return false;
        }

        return $this->waktu_masuk->lt(now()->startOfDay());
    }

    public function getJumlahMalamAttribute(): int
    {
        if (!$this->is_inap || !$this->waktu_masuk) {
            return 0;
        }

        return max(1, $this->waktu_masuk->diffInDays(now()));
    }

    /**
     * Generate kode ticket unik.
     */
    public static function generateTicketCode(): string
    {
        do {
            $code = 'PKR-' . strtoupper(date('Ymd')) . '-' . strtoupper(substr(uniqid(), -5));
        } while (self::where('ticket_code', $code)->exists());

        return $code;
    }

    /**
     * Hitung durasi parkir dalam menit.
     */
    public function getDurasiAttribute(): int
    {
        $selesai = $this->waktu_keluar ?? now();
        return (int) $this->waktu_masuk->diffInMinutes($selesai);
    }

    /**
     * Hitung tarif otomatis berdasarkan durasi.
     * Tarif diambil dari tabel tarifs (dikonfigurasi admin).
     * Jika melewati tengah malam → tarif inap per malam.
     * Jika hari yang sama → tarif per jam (min 1 jam, dibulatkan ke atas).
     */
    public function hitungTarif(): int
    {
        $selesai = $this->waktu_keluar ?? now();

        // Ambil tarif dari DB, fallback ke default jika belum ada
        $tarifRecord = Tarif::getByJenis($this->jenis_kendaraan);

        // Hitung jumlah malam (beda hari kalender)
        $hariMasuk  = $this->waktu_masuk->copy()->startOfDay();
        $hariKeluar = $selesai->copy()->startOfDay();
        $jumlahMalam = (int) $hariMasuk->diffInDays($hariKeluar);

        if ($jumlahMalam >= 1) {
            // Parkir inap: hitung per malam
            return $jumlahMalam * (int) $tarifRecord->tarif_inap;
        }

        // Parkir regular: hitung per jam (minimal 1 jam, dibulatkan ke atas)
        $menit = (int) $this->waktu_masuk->diffInMinutes($selesai);
        $jam   = max(1, (int) ceil($menit / 60));

        return $jam * (int) $tarifRecord->tarif_per_jam;
    }
}