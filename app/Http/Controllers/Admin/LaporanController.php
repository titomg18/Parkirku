<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Parking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        if (auth()->user()->role !== 'admin') abort(403);

        $tanggal = $request->get('tanggal', today()->toDateString());

        // Data harian yang dipilih
        $transaksi = Parking::with('petugas')
            ->whereDate('waktu_masuk', $tanggal)
            ->latest()->get();

        $ringkasan = [
            'total_masuk'      => $transaksi->count(),
            'total_keluar'     => $transaksi->where('status','keluar')->count(),
            'total_inap'       => $transaksi->filter(fn($p) => $p->is_inap && $p->status==='parkir')->count(),
            'pendapatan'       => $transaksi->where('status','keluar')->sum('tarif'),
            'motor'            => $transaksi->where('jenis_kendaraan','motor')->count(),
            'mobil'            => $transaksi->where('jenis_kendaraan','mobil')->count(),
            'truk'             => $transaksi->where('jenis_kendaraan','truk')->count(),
        ];

        // Grafik 30 hari terakhir
        $grafik30 = collect(range(29,0))->map(function($i) {
            $tgl = now()->subDays($i);
            return [
                'label'      => $tgl->isoFormat('D/M'),
                'raw_date'   => $tgl->toDateString(),
                'pendapatan' => Parking::whereDate('waktu_masuk', $tgl->toDateString())
                                    ->where('status','keluar')->sum('tarif'),
                'total'      => Parking::whereDate('waktu_masuk', $tgl->toDateString())->count(),
            ];
        });

        return view('admin.laporan', compact('tanggal','transaksi','ringkasan','grafik30'));
    }
}