<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TarifSeeder extends Seeder
{
    public function run(): void
    {
        $tarifs = [
            ['jenis_kendaraan' => 'motor', 'tarif_per_jam' => 2000,  'tarif_inap' => 15000],
            ['jenis_kendaraan' => 'mobil', 'tarif_per_jam' => 5000,  'tarif_inap' => 40000],
            ['jenis_kendaraan' => 'truk',  'tarif_per_jam' => 10000, 'tarif_inap' => 70000],
        ];

        foreach ($tarifs as $tarif) {
            DB::table('tarifs')->updateOrInsert(
                ['jenis_kendaraan' => $tarif['jenis_kendaraan']],
                array_merge($tarif, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}