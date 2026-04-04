{{-- resources/views/admin/kendaraan.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kendaraan - ParkirKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Courier+Prime:wght@700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(145deg, #f0f4f8 0%, #e8edf2 100%); }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)} }
        .fade-up { animation: fadeInUp 0.4s ease-out forwards; }
        .overlay { transition:opacity 0.3s; opacity:0; visibility:hidden; }
        .overlay.active { opacity:1; visibility:visible; }
        @media(max-width:768px){ .sidebar{transform:translateX(-100%);position:fixed;z-index:100;top:0;left:0;width:280px;height:100vh;} .sidebar.open{transform:translateX(0);} }
        .tab-btn.active { background: #059669; color: white; }
        .tab-btn { transition: all 0.2s; }
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
                    <h1 class="text-2xl font-bold text-gray-800">Data Kendaraan</h1>
                    <p class="text-gray-500 text-sm mt-0.5">Semua riwayat kendaraan parkir</p>
                </div>
                <div class="flex gap-2 text-sm">
                    <div class="px-3 py-2 bg-white border border-gray-200 rounded-xl text-gray-600">
                        Total: <strong class="text-gray-800">{{ $counts['semua'] }}</strong>
                    </div>
                    <div class="px-3 py-2 bg-orange-50 border border-orange-200 rounded-xl text-orange-700">
                        <i class="fas fa-moon text-xs mr-1"></i>Inap: <strong>{{ $counts['inap'] }}</strong>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="flex gap-2 mb-4 overflow-x-auto pb-1 scrollbar-hide flex-wrap">
                @foreach([
                    ['key'=>'semua',  'label'=>'Semua',          'icon'=>'fa-list',          'color'=>'text-gray-600'],
                    ['key'=>'masuk',  'label'=>'Masuk Hari Ini', 'icon'=>'fa-sign-in-alt',   'color'=>'text-emerald-600'],
                    ['key'=>'keluar', 'label'=>'Keluar Hari Ini','icon'=>'fa-sign-out-alt',  'color'=>'text-blue-600'],
                    ['key'=>'inap',   'label'=>'Sedang Inap',    'icon'=>'fa-moon',          'color'=>'text-orange-600'],
                ] as $t)
                <a href="{{ route('admin.kendaraan', array_merge(request()->query(), ['tab'=>$t['key']])) }}"
                   class="tab-btn flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold whitespace-nowrap {{ $tab===$t['key'] ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
                    <i class="fas {{ $t['icon'] }} text-xs"></i>
                    {{ $t['label'] }}
                    <span class="px-1.5 py-0.5 rounded-md text-xs font-bold {{ $tab===$t['key'] ? 'bg-white/30 text-white' : 'bg-gray-100 text-gray-500' }}">{{ $counts[$t['key']] }}</span>
                </a>
                @endforeach
            </div>

            {{-- Filter Bar --}}
            <form method="GET" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-5 flex flex-wrap gap-3 items-center">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <div class="relative flex-1 min-w-36">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="cari" value="{{ $cari }}" placeholder="Cari nomor plat..."
                        class="pl-8 pr-3 py-2 w-full border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <select name="jenis" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 bg-white">
                    <option value="">Semua Jenis</option>
                    <option value="motor" {{ $jenis==='motor'?'selected':'' }}>Motor</option>
                    <option value="mobil" {{ $jenis==='mobil'?'selected':'' }}>Mobil</option>
                    <option value="truk"  {{ $jenis==='truk'?'selected':'' }}>Truk</option>
                </select>
                <input type="date" name="tanggal" value="{{ $tanggal }}"
                    class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition">
                    <i class="fas fa-filter mr-1"></i>Filter
                </button>
                @if($cari || $jenis || $tanggal)
                <a href="{{ route('admin.kendaraan', ['tab'=>$tab]) }}" class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50 transition">
                    <i class="fas fa-times mr-1"></i>Reset
                </a>
                @endif
            </form>

            {{-- Tabel --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-gray-50 text-xs font-bold text-gray-500 uppercase tracking-wider text-left">
                                <th class="px-4 py-3">Ticket</th>
                                <th class="px-4 py-3">No. Kendaraan</th>
                                <th class="px-4 py-3">Jenis</th>
                                <th class="px-4 py-3">Waktu Masuk</th>
                                <th class="px-4 py-3">Waktu Keluar</th>
                                <th class="px-4 py-3">Durasi</th>
                                <th class="px-4 py-3">Status</th>
                                <th class="px-4 py-3">Tarif</th>
                                <th class="px-4 py-3">Petugas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($data as $p)
                            @php
                                $isInap = $p->is_inap;
                                $durasi = $p->durasi;
                                $jam = intdiv($durasi, 60); $menit = $durasi % 60;
                            @endphp
                            <tr class="hover:bg-gray-50 transition {{ $isInap ? 'bg-orange-50/30' : '' }}">
                                <td class="px-4 py-3">
                                    <span class="font-mono text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-lg">{{ $p->ticket_code }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="font-bold tracking-wider text-gray-800 text-sm font-mono">{{ $p->no_kendaraan }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @php $ic = match($p->jenis_kendaraan){'motor'=>'fa-motorcycle','mobil'=>'fa-car','truk'=>'fa-truck',default=>'fa-car'}; $cl = match($p->jenis_kendaraan){'motor'=>'text-indigo-500','mobil'=>'text-emerald-500','truk'=>'text-orange-500',default=>'text-gray-400'}; @endphp
                                    <span class="capitalize {{ $cl }} text-xs font-semibold"><i class="fas {{ $ic }} mr-1"></i>{{ $p->jenis_kendaraan }}</span>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">
                                    <div class="font-semibold">{{ $p->waktu_masuk->format('d/m/Y') }}</div>
                                    <div class="text-gray-400">{{ $p->waktu_masuk->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-600">
                                    @if($p->waktu_keluar)
                                    <div class="font-semibold">{{ $p->waktu_keluar->format('d/m/Y') }}</div>
                                    <div class="text-gray-400">{{ $p->waktu_keluar->format('H:i') }} WIB</div>
                                    @else
                                    <span class="text-gray-300">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    @if($isInap)
                                    <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded-lg font-semibold">
                                        <i class="fas fa-moon text-xs mr-1"></i>{{ $p->jumlah_malam }} malam
                                    </span>
                                    @else
                                    <span class="text-gray-600">{{ $jam > 0 ? $jam.'j ' : '' }}{{ $menit }}m</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($p->status === 'parkir')
                                        @if($isInap)
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-bold">
                                            <i class="fas fa-moon text-xs"></i> Inap
                                        </span>
                                        @else
                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">
                                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span> Parkir
                                        </span>
                                        @endif
                                    @else
                                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">Keluar</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-xs font-bold text-emerald-600">
                                    @if($p->tarif) Rp {{ number_format($p->tarif,0,',','.') }} @else <span class="text-gray-300">—</span> @endif
                                </td>
                                <td class="px-4 py-3 text-xs text-gray-500">{{ $p->petugas?->name ?? '—' }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="9" class="px-4 py-14 text-center text-gray-400">
                                <i class="fas fa-car text-4xl mb-3 block opacity-20"></i>
                                <p class="text-sm">Tidak ada data kendaraan</p>
                            </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                @if($data->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $data->links() }}
                </div>
                @endif
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
    window.addEventListener('resize',()=>{if(window.innerWidth>768)closeSidebar();});
</script>
</body>
</html>