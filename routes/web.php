<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Petugas\ParkingController;
use App\Models\Parking;
use App\Models\User;

// ================== ROOT ==================
Route::get('/', function () {
    return redirect()->route('login');
});

// ================== AUTH ==================
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginPost']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// ================== ADMIN ==================
Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role != 'admin') {
            abort(403);
        }

        $masukHariIni = Parking::whereDate('waktu_masuk', today())->count();
        $keluarHariIni = Parking::whereDate('waktu_keluar', today())->count();
        $sedangParkir = Parking::where('status', 'parkir')->count();
        $inapSekarang = Parking::where('status', 'parkir')
            ->whereDate('waktu_masuk', '<', today())
            ->count();
        $pendapatanHariIni = Parking::whereDate('waktu_keluar', today())->sum('tarif');
        $totalPetugas = User::where('role', 'petugas')->count();
        $komposisi = Parking::selectRaw('jenis_kendaraan, count(*) as total')
            ->where('status', 'parkir')
            ->groupBy('jenis_kendaraan')
            ->get();
        $grafik7Hari = collect(range(6, 0))->map(function ($i) {
            $tgl = now()->subDays($i);
            return [
                'label' => $tgl->format('D'),
                'masuk' => Parking::whereDate('waktu_masuk', $tgl->toDateString())->count(),
                'pendapatan' => Parking::whereDate('waktu_keluar', $tgl->toDateString())->sum('tarif'),
            ];
        });
        $aktivitas = Parking::latest()->take(10)->get();

        return view('admin.dashboard', compact('masukHariIni', 'keluarHariIni', 'sedangParkir', 'inapSekarang', 'pendapatanHariIni', 'totalPetugas', 'komposisi', 'grafik7Hari', 'aktivitas'));
    })->name('dashboard');

    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');

    Route::get('/profile', function () {
        if (auth()->user()->role != 'admin') {
            abort(403);
        }
        return view('admin.profile');
    })->name('profile');

    Route::get('/kendaraan', function (Request $request) {
        if (auth()->user()->role != 'admin') {
            abort(403);
        }

        $tab = $request->get('tab', 'semua');
        $cari = $request->get('cari', '');
        $jenis = $request->get('jenis', '');
        $tanggal = $request->get('tanggal', today()->toDateString());

        $query = Parking::with('petugas');

        if ($tab === 'masuk') {
            $query->whereDate('waktu_masuk', $tanggal);
        } elseif ($tab === 'keluar') {
            $query->whereDate('waktu_keluar', $tanggal);
        } elseif ($tab === 'inap') {
            $query->where('status', 'parkir')
                ->whereDate('waktu_masuk', '<', today());
        } else {
            $query->whereDate('waktu_masuk', $tanggal);
        }

        if ($cari) {
            $query->where('no_kendaraan', 'like', "%{$cari}%");
        }

        if ($jenis) {
            $query->where('jenis_kendaraan', $jenis);
        }

        $data = $query->latest()->paginate(15)->withQueryString();
        $counts = [
            'semua' => Parking::count(),
            'masuk' => Parking::whereDate('waktu_masuk', $tanggal)->count(),
            'keluar' => Parking::whereDate('waktu_keluar', $tanggal)->count(),
            'inap' => Parking::where('status', 'parkir')->whereDate('waktu_masuk', '<', today())->count(),
        ];

        return view('admin.kendaraan', compact('tab', 'cari', 'jenis', 'tanggal', 'data', 'counts'));
    })->name('kendaraan');

    Route::get('/tarif', function () {
        if (auth()->user()->role != 'admin') {
            abort(403);
        }

        $tarifs = collect([
            (object)['id' => 1, 'jenis_kendaraan' => 'motor', 'tarif_per_jam' => 2000, 'tarif_inap' => 15000],
            (object)['id' => 2, 'jenis_kendaraan' => 'mobil', 'tarif_per_jam' => 5000, 'tarif_inap' => 40000],
            (object)['id' => 3, 'jenis_kendaraan' => 'truk', 'tarif_per_jam' => 10000, 'tarif_inap' => 70000],
        ]);

        return view('admin.tarif', compact('tarifs'));
    })->name('tarif');

    Route::put('/tarif/{id}', function () {
        return redirect()->route('admin.tarif')->with('success', 'Tarif berhasil diperbarui!');
    })->name('tarif.update');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

// ================== PETUGAS ==================
Route::middleware('auth')->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', [ParkingController::class, 'index'])->name('dashboard');
    Route::get('/masuk', [ParkingController::class, 'masukIndex'])->name('masuk.index');
    Route::post('/masuk', [ParkingController::class, 'masuk'])->name('masuk');
    Route::get('/karcis/{ticketCode}', [ParkingController::class, 'karcis'])->name('karcis');
    Route::get('/keluar', [ParkingController::class, 'keluarIndex'])->name('keluar');
    Route::post('/keluar', [ParkingController::class, 'keluarProses'])->name('keluar.proses');
});