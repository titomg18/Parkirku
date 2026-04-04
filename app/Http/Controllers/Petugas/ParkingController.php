<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Parking;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    /**
     * Dashboard petugas — tabel semua data parkir.
     */
    public function index()
    {
        $parkings     = Parking::with('petugas')->latest()->paginate(15);
        $totalParkir  = Parking::where('status', 'parkir')->count();
        $totalHariIni = Parking::whereDate('waktu_masuk', today())->count();
        $totalKeluar  = Parking::whereDate('waktu_masuk', today())->where('status', 'keluar')->count();
        $pendapatan   = Parking::whereDate('waktu_masuk', today())->where('status', 'keluar')->sum('tarif');

        return view('petugas.dashboard', compact('parkings', 'totalParkir', 'totalHariIni', 'totalKeluar', 'pendapatan'));
    }

    /**
     * Halaman form kendaraan masuk.
     */
    public function masukIndex()
    {
        return view('petugas.masuk');
    }

    /**
     * Simpan kendaraan masuk & tampilkan karcis.
     */
    public function masuk(Request $request)
    {
        $request->validate([
            'no_kendaraan'    => 'required|string|max:20',
            'jenis_kendaraan' => 'required|in:motor,mobil,truk',
        ], [
            'no_kendaraan.required'    => 'Nomor kendaraan wajib diisi.',
            'jenis_kendaraan.required' => 'Jenis kendaraan wajib dipilih.',
        ]);

        $parking = Parking::create([
            'ticket_code'     => Parking::generateTicketCode(),
            'no_kendaraan'    => strtoupper(str_replace(' ', '', $request->no_kendaraan)),
            'jenis_kendaraan' => $request->jenis_kendaraan,
            'waktu_masuk'     => now(),
            'status'          => 'parkir',
            'petugas_id'      => auth()->id(),
        ]);

        return redirect()->route('petugas.masuk.index')
            ->with('show_karcis', $parking->ticket_code);
    }

    /**
     * Tampilkan & cetak karcis.
     */
    public function karcis(string $ticketCode)
    {
        $parking = Parking::where('ticket_code', $ticketCode)->firstOrFail();
        return view('petugas.karcis', compact('parking'));
    }

    /**
     * Halaman kendaraan keluar — scan / input kode.
     */
    public function keluarIndex()
    {
        $riwayat = Parking::where('status', 'keluar')
            ->whereDate('waktu_keluar', today())
            ->latest('waktu_keluar')
            ->take(10)
            ->get();

        return view('petugas.keluar', compact('riwayat'));
    }

    /**
     * Proses kendaraan keluar.
     */
    public function keluarProses(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|string',
        ], [
            'ticket_code.required' => 'Kode tiket wajib diisi.',
        ]);

        $parking = Parking::where('ticket_code', strtoupper($request->ticket_code))
            ->where('status', 'parkir')
            ->first();

        if (!$parking) {
            return back()->with('error', 'Kode tiket tidak ditemukan atau kendaraan sudah keluar.');
        }

        $parking->update([
            'waktu_keluar' => now(),
            'status'       => 'keluar',
            'tarif'        => $parking->hitungTarif(),
        ]);

        return redirect()->route('petugas.keluar')->with('sukses_keluar', $parking->ticket_code);
    }
}