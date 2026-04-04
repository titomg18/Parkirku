{{-- resources/views/admin/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - ParkirKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(145deg, #f0f4f8 0%, #e8edf2 100%); }
        @keyframes fadeInUp { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:translateY(0); } }
        .fade-up { animation: fadeInUp 0.5s ease-out forwards; }
        .overlay { transition: opacity 0.3s ease; opacity:0; visibility:hidden; }
        .overlay.active { opacity:1; visibility:visible; }
        @media(max-width:768px){ .sidebar{transform:translateX(-100%);position:fixed;z-index:100;top:0;left:0;width:280px;height:100vh;box-shadow:2px 0 20px rgba(0,0,0,.2);} .sidebar.open{transform:translateX(0);} }
        ::-webkit-scrollbar{width:5px;height:5px;} ::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:10px;}
        .stat-card { transition: all 0.25s; }
        .stat-card:hover { transform: translateY(-3px); }
    </style>
</head>
<body>
@include('admin.partials.navbar')
<div id="overlay" class="overlay fixed inset-0 bg-black/50 z-50 lg:hidden"></div>
<div class="flex pt-16 min-h-screen">
    @include('admin.partials.sidebar')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
        <div class="fade-up">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-3">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Selamat datang, <span class="font-semibold text-emerald-600">{{ auth()->user()->name }}</span> · {{ now()->isoFormat('dddd, D MMMM Y') }}</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1.5 bg-emerald-100 text-emerald-700 rounded-xl text-xs font-bold">Administrator</span>
                    <a href="{{ route('admin.laporan') }}" class="px-4 py-1.5 bg-blue-600 text-white rounded-xl text-xs font-bold hover:bg-blue-700 transition flex items-center gap-1.5">
                        <i class="fas fa-file-alt"></i> Lihat Laporan
                    </a>
                </div>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4 mb-6">
                <div class="stat-card col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:shadow-md hover:border-emerald-200">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Masuk Hari Ini</p>
                        <div class="w-8 h-8 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-sign-in-alt text-emerald-600 text-xs"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $masukHariIni }}</p>
                    <p class="text-xs text-emerald-600 mt-1 font-medium">kendaraan masuk</p>
                </div>
                <div class="stat-card col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:shadow-md hover:border-blue-200">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Keluar Hari Ini</p>
                        <div class="w-8 h-8 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-sign-out-alt text-blue-600 text-xs"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $keluarHariIni }}</p>
                    <p class="text-xs text-blue-600 mt-1 font-medium">kendaraan keluar</p>
                </div>
                <div class="stat-card col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:shadow-md hover:border-indigo-200">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Sedang Parkir</p>
                        <div class="w-8 h-8 bg-indigo-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-car text-indigo-600 text-xs"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $sedangParkir }}</p>
                    <p class="text-xs text-indigo-600 mt-1 font-medium">aktif parkir</p>
                </div>
                <div class="stat-card col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:shadow-md hover:border-orange-200">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Kendaraan Inap</p>
                        <div class="w-8 h-8 bg-orange-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-moon text-orange-500 text-xs"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $inapSekarang }}</p>
                    <p class="text-xs text-orange-500 mt-1 font-medium">kendaraan inap</p>
                </div>
                <div class="stat-card col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:shadow-md hover:border-yellow-200">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pendapatan</p>
                        <div class="w-8 h-8 bg-yellow-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-money-bill text-yellow-600 text-xs"></i>
                        </div>
                    </div>
                    <p class="text-xl font-bold text-gray-800">Rp {{ number_format($pendapatanHariIni,0,',','.') }}</p>
                    <p class="text-xs text-yellow-600 mt-1 font-medium">hari ini</p>
                </div>
                <div class="stat-card col-span-1 bg-white rounded-2xl shadow-sm border border-gray-100 p-4 hover:shadow-md hover:border-purple-200">
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Petugas</p>
                        <div class="w-8 h-8 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-users text-purple-600 text-xs"></i>
                        </div>
                    </div>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalPetugas }}</p>
                    <p class="text-xs text-purple-600 mt-1 font-medium">petugas aktif</p>
                </div>
            </div>

            {{-- Charts Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 mb-6">

                {{-- Grafik 7 Hari --}}
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h3 class="font-semibold text-gray-800">Statistik 7 Hari Terakhir</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Pendapatan & kendaraan masuk harian</p>
                        </div>
                        <div class="flex gap-3 text-xs">
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500 inline-block"></span>Pendapatan</span>
                            <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-400 inline-block"></span>Kendaraan</span>
                        </div>
                    </div>
                    <div style="height:220px">
                        <canvas id="grafikChart"></canvas>
                    </div>
                </div>

                {{-- Donut Komposisi --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                    <div class="mb-4">
                        <h3 class="font-semibold text-gray-800">Komposisi Parkir</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Kendaraan sedang parkir saat ini</p>
                    </div>
                    <div style="height:160px" class="relative">
                        <canvas id="donutChart"></canvas>
                        @if($sedangParkir === 0)
                        <div class="absolute inset-0 flex items-center justify-center">
                            <p class="text-xs text-gray-400">Tidak ada kendaraan</p>
                        </div>
                        @endif
                    </div>
                    <div class="mt-4 space-y-2">
                        @foreach($komposisi as $k)
                        @php
                            $color = match($k->jenis_kendaraan){'motor'=>'indigo','mobil'=>'emerald','truk'=>'orange',default=>'gray'};
                            $icon  = match($k->jenis_kendaraan){'motor'=>'fa-motorcycle','mobil'=>'fa-car','truk'=>'fa-truck',default=>'fa-car'};
                        @endphp
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-2 text-sm text-gray-600">
                                <i class="fas {{ $icon }} text-{{ $color }}-500 w-4 text-center"></i>
                                {{ ucfirst($k->jenis_kendaraan) }}
                            </span>
                            <span class="font-bold text-gray-800 text-sm">{{ $k->total }}</span>
                        </div>
                        @endforeach
                        @if($komposisi->isEmpty())
                        <p class="text-xs text-gray-400 text-center py-2">Tidak ada data</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Aktivitas Terbaru + Quick Stats --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

                {{-- Aktivitas --}}
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800">Aktivitas Terbaru</h3>
                            <p class="text-xs text-gray-400 mt-0.5">10 transaksi terakhir</p>
                        </div>
                        <a href="{{ route('admin.kendaraan') }}" class="text-xs text-blue-600 hover:text-blue-700 font-semibold">Lihat Semua →</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 text-xs text-gray-500 uppercase tracking-wider">
                                    <th class="px-4 py-3 text-left">Kendaraan</th>
                                    <th class="px-4 py-3 text-left">Jenis</th>
                                    <th class="px-4 py-3 text-left">Masuk</th>
                                    <th class="px-4 py-3 text-left">Status</th>
                                    <th class="px-4 py-3 text-right">Tarif</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($aktivitas as $a)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3">
                                        <span class="font-bold text-gray-800 tracking-wider text-xs font-mono">{{ $a->no_kendaraan }}</span>
                                        <p class="text-xs text-gray-400 font-mono">{{ $a->ticket_code }}</p>
                                    </td>
                                    <td class="px-4 py-3">
                                        @php $ic = match($a->jenis_kendaraan){'motor'=>'fa-motorcycle','mobil'=>'fa-car','truk'=>'fa-truck',default=>'fa-car'}; @endphp
                                        <span class="text-xs text-gray-600 capitalize"><i class="fas {{ $ic }} mr-1 text-gray-400"></i>{{ $a->jenis_kendaraan }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-500">
                                        {{ $a->waktu_masuk->format('d/m H:i') }}
                                        @if($a->is_inap)
                                        <span class="ml-1 px-1.5 py-0.5 bg-orange-100 text-orange-600 rounded text-xs font-semibold">INAP</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($a->status === 'parkir')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span>Parkir
                                        </span>
                                        @else
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">Keluar</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right text-xs font-semibold text-emerald-600">
                                        @if($a->tarif) Rp {{ number_format($a->tarif,0,',','.') }} @else <span class="text-gray-300">—</span> @endif
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="px-4 py-10 text-center text-gray-400 text-sm">Belum ada aktivitas</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Quick Links --}}
                <div class="space-y-4">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <h3 class="font-semibold text-gray-800 mb-4">Menu Cepat</h3>
                        <div class="space-y-2">
                            <a href="{{ route('admin.kendaraan') }}" class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-emerald-50 hover:text-emerald-700 transition text-sm text-gray-700 font-medium">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center"><i class="fas fa-car text-emerald-600 text-sm"></i></div>
                                Data Kendaraan
                            </a>
                            <a href="{{ route('admin.tarif') }}" class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-blue-50 hover:text-blue-700 transition text-sm text-gray-700 font-medium">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center"><i class="fas fa-tags text-blue-600 text-sm"></i></div>
                                Kelola Tarif
                            </a>
                            <a href="{{ route('admin.laporan') }}" class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-purple-50 hover:text-purple-700 transition text-sm text-gray-700 font-medium">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center"><i class="fas fa-chart-bar text-purple-600 text-sm"></i></div>
                                Laporan Harian
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-orange-50 hover:text-orange-700 transition text-sm text-gray-700 font-medium">
                                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center"><i class="fas fa-users text-orange-600 text-sm"></i></div>
                                Manajemen User
                            </a>
                            <a href="{{ route('admin.profile') }}" class="flex items-center gap-3 p-3 rounded-xl bg-gray-50 hover:bg-indigo-50 hover:text-indigo-700 transition text-sm text-gray-700 font-medium">
                                <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center"><i class="fas fa-user-cog text-indigo-600 text-sm"></i></div>
                                Profil & Pengaturan
                            </a>
                        </div>
                    </div>

                    {{-- Info Inap --}}
                    @if($inapSekarang > 0)
                    <div class="bg-orange-50 border-2 border-orange-200 rounded-2xl p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="fas fa-moon text-orange-500"></i>
                            <span class="font-bold text-orange-700 text-sm">Kendaraan Inap!</span>
                        </div>
                        <p class="text-xs text-orange-600">Ada <strong>{{ $inapSekarang }}</strong> kendaraan yang bermalam. Tarif inap berlaku otomatis setelah lewat tengah malam.</p>
                        <a href="{{ route('admin.kendaraan', ['tab'=>'inap']) }}" class="mt-2 block text-xs text-orange-700 font-semibold hover:underline">Lihat detail →</a>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </main>
</div>
@include('admin.partials.footer')
<script>
    // Sidebar
    const sidebar=document.getElementById('sidebar'),toggleBtn=document.getElementById('sidebarToggle'),overlay=document.getElementById('overlay');
    function closeSidebar(){sidebar.classList.remove('open');overlay.classList.remove('active');}
    function openSidebar(){sidebar.classList.add('open');overlay.classList.add('active');}
    toggleBtn.addEventListener('click',e=>{e.stopPropagation();sidebar.classList.contains('open')?closeSidebar():openSidebar();});
    overlay.addEventListener('click',closeSidebar);
    window.addEventListener('resize',()=>{if(window.innerWidth>768)closeSidebar();});

    // Data dari PHP
    const grafikData = @json($grafik7Hari);
    const komposisiData = @json($komposisi);

    // Bar/Line Chart - 7 hari
    const ctx1 = document.getElementById('grafikChart').getContext('2d');
    new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: grafikData.map(d => d.label),
            datasets: [
                {
                    type: 'line',
                    label: 'Kendaraan Masuk',
                    data: grafikData.map(d => d.masuk),
                    borderColor: '#60a5fa',
                    backgroundColor: 'rgba(96,165,250,0.1)',
                    borderWidth: 2,
                    pointRadius: 4,
                    pointBackgroundColor: '#60a5fa',
                    tension: 0.4,
                    yAxisID: 'y1',
                },
                {
                    type: 'bar',
                    label: 'Pendapatan (Rp)',
                    data: grafikData.map(d => d.pendapatan),
                    backgroundColor: 'rgba(16,185,129,0.75)',
                    borderRadius: 6,
                    yAxisID: 'y',
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y:  { position:'left',  grid:{color:'#f1f5f9'}, ticks:{callback: v => 'Rp '+Number(v).toLocaleString('id'), font:{size:10}} },
                y1: { position:'right', grid:{display:false},    ticks:{font:{size:10}}, beginAtZero:true },
                x:  { grid:{display:false}, ticks:{font:{size:11}} }
            }
        }
    });

    // Donut Chart
    const ctx2 = document.getElementById('donutChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: komposisiData.map(k => k.jenis_kendaraan),
            datasets: [{
                data: komposisiData.map(k => k.total),
                backgroundColor: ['#6366f1','#10b981','#f97316'],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false, cutout: '70%',
            plugins: { legend: { display: false } }
        }
    });
</script>
</body>
</html>