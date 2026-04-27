<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tarif;
use Illuminate\Http\Request;

class TarifController extends Controller
{
    public function index()
    {
        if (auth()->user()->role != 'admin') {
            abort(403);
        }

        $tarifs = Tarif::orderByRaw("FIELD(jenis_kendaraan, 'motor', 'mobil', 'truk')")->get();

        // Pastikan semua jenis kendaraan ada
        $jenisKendaraan = ['motor', 'mobil', 'truk'];
        foreach ($jenisKendaraan as $jenis) {
            if (!$tarifs->contains('jenis_kendaraan', $jenis)) {
                Tarif::getByJenis($jenis);
            }
        }

        // Reload setelah pastikan semua ada
        $tarifs = Tarif::orderByRaw("FIELD(jenis_kendaraan, 'motor', 'mobil', 'truk')")->get();

        return view('admin.tarif', compact('tarifs'));
    }

    public function update(Request $request, $id)
    {
        if (auth()->user()->role != 'admin') {
            abort(403);
        }

        $request->validate([
            'tarif_per_jam' => 'required|numeric|min:0',
            'tarif_inap'    => 'required|numeric|min:0',
        ], [
            'tarif_per_jam.required' => 'Tarif per jam wajib diisi.',
            'tarif_per_jam.numeric'  => 'Tarif per jam harus berupa angka.',
            'tarif_per_jam.min'      => 'Tarif per jam tidak boleh negatif.',
            'tarif_inap.required'    => 'Tarif inap wajib diisi.',
            'tarif_inap.numeric'     => 'Tarif inap harus berupa angka.',
            'tarif_inap.min'         => 'Tarif inap tidak boleh negatif.',
        ]);

        $tarif = Tarif::findOrFail($id);
        $tarif->update([
            'tarif_per_jam' => (int) $request->tarif_per_jam,
            'tarif_inap'    => (int) $request->tarif_inap,
        ]);

        return redirect()->route('admin.tarif')
            ->with('success', 'Tarif ' . ucfirst($tarif->jenis_kendaraan) . ' berhasil diperbarui! Petugas akan otomatis menggunakan tarif baru.');
    }
}