<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     * Motor: Rp 2.000/jam, Mobil: Rp 5.000/jam, Truk: Rp 10.000/jam
     */
    public function hitungTarif(): int
    {
        $jam = max(1, ceil($this->durasi / 60)); // minimal 1 jam
        $tarifPerJam = match($this->jenis_kendaraan) {
            'mobil' => 5000,
            'truk'  => 10000,
            default => 2000, // motor
        };
        return $jam * $tarifPerJam;
    }
}