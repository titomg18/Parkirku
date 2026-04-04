{{-- resources/views/admin/laporan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - ParkirKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Courier+Prime:wght@700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(145deg, #f0f4f8 0%, #e8edf2 100%); }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)} }
        .fade-up { animation: fadeInUp 0.4s ease-out forwards; }
        .overlay { transition:opacity 0.3s; opacity:0; visibility:hidden; }
        .overlay.active { opacity:1; visibility:visible; }
        @media(max-width:768px){ .sidebar{transform:translateX(-100%);position:fixed;z-index:100;top:0;left:0;width:280px;height:100vh;} .sidebar.open{transform:translateX(0);} }
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .print-area { box-shadow: none !important; }
        }
    </style>
</head>
<body>
@include('admin.partials.navbar')
<div id="overlay" class="overlay fixed inset-0 bg-black/50 z-50 lg:hidden no-print"></div>
<div class="flex pt-16 min-h-screen">
    @include('admin.partials.sidebar')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
        <div class="fade-up">

            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 gap-3 no-print">
                <div>
                    <h1 class="text-2xl font-bold text-gray-800">Laporan Parkir</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Laporan harian & grafik 30 hari terakhir</p>
                </div>
                <div class="flex gap-2">
                    <form method="GET" class="flex gap-2 items-center">
                        <input type="date" name="tanggal" value="{{ $tanggal }}"
                            class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300">
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold hover:bg-blue-700 transition">
                            <i class="fas fa-search mr-1"></i>Tampilkan
                        </button>
                    </form>
                    <button onclick="window.print()" class="px-4 py-2 bg-gray-700 text-white rounded-xl text-sm font-bold hover:bg-gray-800 transition flex items-center gap-1.5">
                        <i class="fas fa-print"></i> Cetak
                    </button>
                </div>
            </div>

            {{-- Judul Laporan --}}
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-5 mb-5 text-white print-area">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold">Laporan Harian Parkir</h2>
                        <p class="text-blue-200 text-sm mt-0.5">
                            {{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM Y') }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                        <i class="fas fa-chart-bar text-2xl"></i>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Hari Ini --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-7 gap-3 mb-5 print-area">
                @php
                $cards = [
                    ['val'=>$ringkasan['total_masuk'],  'label'=>'Total Masuk',    'icon'=>'fa-sign-in-alt',  'color'=>'emerald'],
                    ['val'=>$ringkasan['total_keluar'], 'label'=>'Total Keluar',   'icon'=>'fa-sign-out-alt', 'color'=>'blue'],
                    ['val'=>$ringkasan['total_inap'],   'label'=>'Inap',           'icon'=>'fa-moon',         'color'=>'orange'],
                    ['val'=>'Rp '.number_format($ringkasan['pendapatan'],0,',','.'), 'label'=>'Pendapatan', 'icon'=>'fa-money-bill','color'=>'yellow','text'=>true],
                    ['val'=>$ringkasan['motor'],        'label'=>'Motor',          'icon'=>'fa-motorcycle',   'color'=>'indigo'],
                    ['val'=>$ringkasan['mobil'],        'label'=>'Mobil',          'icon'=>'fa-car',          'color'=>'teal'],
                    ['val'=>$ringkasan['truk'],         'label'=>'Truk',           'icon'=>'fa-truck',        'color'=>'red'],
                ];
                @endphp
                @foreach($cards as $card)
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                    <div class="w-8 h-8 bg-{{ $card['color'] }}-100 rounded-xl flex items-center justify-center mb-2">
                        <i class="fas {{ $card['icon'] }} text-{{ $card['color'] }}-600 text-sm"></i>
                    </div>
                    <p class="text-xs text-gray-500 mb-0.5">{{ $card['label'] }}</p>
                    <p class="{{ isset($card['text']) ? 'text-sm' : 'text-2xl' }} font-bold text-gray-800">{{ $card['val'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- Grafik 30 Hari --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-5 no-print">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-5 gap-2">
                    <div>
                        <h3 class="font-bold text-gray-800">Grafik Pendapatan 30 Hari Terakhir</h3>
                        <p class="text-xs text-gray-400 mt-0.5">Klik bar untuk melihat laporan hari tersebut</p>
                    </div>
                    <div class="flex gap-3 text-xs">
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span>Pendapatan</span>
                        <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-400 inline-block"></span>Kendaraan</span>
                    </div>
                </div>
                <div style="height:280px">
                    <canvas id="grafikBesar"></canvas>
                </div>
            </div>

            {{-- Tabel Transaksi --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden print-area">
                <div class="p-4 sm:p-5 border-b border-gray-100 flex items-center justify-between no-print">
                    <div>
                        <h3 class="font-semibold text-gray-800">Detail Transaksi</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y') }} — {{ $transaksi->count() }} transaksi</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider text-left">
                                <th class="px-4 py-3">Ticket</th>
                                <th class="px-4 py-3">Kendaraan</th>
                                <th class="px-4 py-3">Jenis</th>
                                <th class="px-4 py-3">Masuk</th>
                                <th class="px-4 py-3">Keluar</th>
                                <th class="px-4 py-3">Durasi</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3 text-right">Tarif</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($transaksi as $p)
                            @php
                                $dur = $p->durasi; $jam = intdiv($dur,60); $mnt = $dur%60;
                            @endphp
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3"><span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded-lg text-gray-500">{{ $p->ticket_code }}</span></td>
                                <td class="px-4 py-3"><span class="font-bold tracking-wider text-gray-800 text-xs font-mono">{{ $p->no_kendaraan }}</span></td>
                                <td class="px-4 py-3">
                                    @php $ic=match($p->jenis_kendaraan){'motor'=>'fa-motorcycle','mobil'=>'fa-car','truk'=>'fa-truck',default=>'fa-car'}; @endphp
                                    <span class="capitalize text-xs text-gray-600"><i class="fas {{ $ic }} mr-1 text-gray-400"></i>{{ $p->jenis_kendaraan }}</span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">{{ $p->waktu_masuk->format('H:i') }}</td>
                                <td class="px-4 py-3 text-xs text-gray-500">{{ $p->waktu_keluar?->format('H:i') ?? '—' }}</td>
                                <td class="px-4 py-3 text-xs text-gray-600">
                                    @if($p->is_inap)
                                    <span class="px-1.5 py-0.5 bg-orange-100 text-orange-700 rounded text-xs font-bold">{{ $p->jumlah_malam }}m inap</span>
                                    @else
                                    {{ $jam > 0 ? $jam.'j ' : '' }}{{ $mnt }}m
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($p->status==='parkir')
                                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">Parkir</span>
                                    @else
                                    <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded-full text-xs font-bold">Keluar</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right text-xs font-bold text-emerald-600">
                                    @if($p->tarif) Rp {{ number_format($p->tarif,0,',','.') }} @else <span class="text-gray-300">—</span> @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="8" class="px-4 py-14 text-center text-gray-400">
                                <i class="fas fa-receipt text-4xl mb-3 block opacity-20"></i>
                                <p class="text-sm">Tidak ada transaksi pada {{ \Carbon\Carbon::parse($tanggal)->isoFormat('D MMMM Y') }}</p>
                            </td></tr>
                            @endforelse
                        </tbody>
                        @if($transaksi->where('status','keluar')->isNotEmpty())
                        <tfoot>
                            <tr class="bg-emerald-50">
                                <td colspan="7" class="px-4 py-3 text-sm font-bold text-emerald-800 text-right">Total Pendapatan:</td>
                                <td class="px-4 py-3 text-right text-sm font-bold text-emerald-700">Rp {{ number_format($ringkasan['pendapatan'],0,',','.') }}</td>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>

        </div>
    </main>
</div>
@include('admin.partials.footer')
<script>
    const sidebar=document.getElementById('sidebar'),toggleBtn=document.getElementById('sidebarToggle'),overlay=document.getElementById('overlay');
    function closeSidebar(){sidebar.classList.remove('open');overlay.classList.remove('active');}
    function openSidebar(){sidebar.classList.add('open');overlay.classList.add('active');}
    toggleBtn.addEventListener('click',e=>{e.stopPropagation();sidebar.classList.contains('open')?closeSidebar():openSidebar();});
    overlay.addEventListener('click',closeSidebar);

    const grafik30 = @json($grafik30);

    const ctx = document.getElementById('grafikBesar').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: grafik30.map(d => d.label),
            datasets: [
                {
                    type: 'line',
                    label: 'Kendaraan',
                    data: grafik30.map(d => d.total),
                    borderColor: '#34d399',
                    backgroundColor: 'rgba(52,211,153,0.1)',
                    borderWidth: 2,
                    pointRadius: 3,
                    pointBackgroundColor: '#34d399',
                    tension: 0.4,
                    yAxisID: 'y1',
                },
                {
                    type: 'bar',
                    label: 'Pendapatan',
                    data: grafik30.map(d => d.pendapatan),
                    backgroundColor: grafik30.map((d,i) => d.label === '{{ \Carbon\Carbon::parse($tanggal)->isoFormat("D/M") }}' ? '#6366f1' : 'rgba(59,130,246,0.65)'),
                    borderRadius: 4,
                    yAxisID: 'y',
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ctx.dataset.label === 'Pendapatan'
                            ? 'Rp ' + Number(ctx.raw).toLocaleString('id-ID')
                            : ctx.raw + ' kendaraan'
                    }
                }
            },
            scales: {
                y:  { position:'left',  grid:{color:'#f1f5f9'}, ticks:{callback:v=>'Rp '+Number(v).toLocaleString('id-ID',{notation:'compact'}), font:{size:10}} },
                y1: { position:'right', grid:{display:false}, ticks:{font:{size:10}}, beginAtZero:true },
                x:  { grid:{display:false}, ticks:{font:{size:9}, maxRotation:45} }
            },
            onClick: (e, elements) => {
                if(elements.length > 0) {
                    const idx = elements[0].index;
                    const rawDate = grafik30[idx].raw_date;
                    if(rawDate) window.location.href = '?tanggal=' + rawDate;
                }
            }
        }
    });
</script>
</body>
</html>