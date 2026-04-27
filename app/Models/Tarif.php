<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    protected $fillable = [
        'jenis_kendaraan',
        'tarif_per_jam',
        'tarif_inap',
    ];

    /**
     * Ambil tarif berdasarkan jenis kendaraan.
     * Jika tidak ada di DB, gunakan default.
     */
    public static function getByJenis(string $jenis): self
    {
        return self::firstOrCreate(
            ['jenis_kendaraan' => $jenis],
            [
                'tarif_per_jam' => match($jenis) {
                    'mobil' => 5000,
                    'truk'  => 10000,
                    default => 2000,
                },
                'tarif_inap' => match($jenis) {
                    'mobil' => 40000,
                    'truk'  => 70000,
                    default => 15000,
                },
            ]
        );
    }
}