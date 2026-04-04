{{-- resources/views/petugas/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - ParkirKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(145deg, #f0f4f8 0%, #e2e8f0 100%); }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-fade-up { animation: fadeInUp 0.5s ease-out forwards; }
        .sidebar-transition { transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .overlay { transition: opacity 0.3s ease; opacity: 0; visibility: hidden; }
        .overlay.active { opacity: 1; visibility: visible; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); position: fixed; z-index: 100; top: 0; left: 0; width: 280px; height: 100vh; box-shadow: 2px 0 20px rgba(0,0,0,0.2); }
            .sidebar.open { transform: translateX(0); }
            .overlay { z-index: 90; }
        }
        ::-webkit-scrollbar { width: 6px; } ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; } ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body>
    @include('petugas.partials.navbar')
    <div id="overlay" class="overlay fixed inset-0 bg-black/50 z-50 lg:hidden"></div>
    <div class="flex pt-16 min-h-screen">
        @include('petugas.partials.sidebar')
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="animate-fade-up">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Dashboard Petugas</h1>
                        <p class="text-gray-500 text-sm mt-1">Data kendaraan parkir keseluruhan</p>
                    </div>
                    <a href="{{ route('petugas.masuk.index') }}"
                        class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition text-sm">
                        <i class="fas fa-plus"></i> Kendaraan Masuk
                    </a>
                </div>

                {{-- Statistik --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                    <div class="group bg-white rounded-2xl shadow-sm hover:shadow-md transition p-4 border border-gray-100 hover:border-blue-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium">Sedang Parkir</p>
                                <p class="text-2xl font-bold text-blue-600 mt-1">{{ $totalParkir }}</p>
                            </div>
                            <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-car text-blue-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="group bg-white rounded-2xl shadow-sm hover:shadow-md transition p-4 border border-gray-100 hover:border-emerald-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium">Masuk Hari Ini</p>
                                <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $totalHariIni }}</p>
                            </div>
                            <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-sign-in-alt text-emerald-600"></i>
                            </div>
                        </div>
                    </div>
                    <div class="group bg-white rounded-2xl shadow-sm hover:shadow-md transition p-4 border border-gray-100 hover:border-orange-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium">Keluar Hari Ini</p>
                                <p class="text-2xl font-bold text-orange-500 mt-1">{{ $totalKeluar }}</p>
                            </div>
                            <div class="w-10 h-10 bg-orange-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-sign-out-alt text-orange-500"></i>
                            </div>
                        </div>
                    </div>
                    <div class="group bg-white rounded-2xl shadow-sm hover:shadow-md transition p-4 border border-gray-100 hover:border-yellow-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs font-medium">Pendapatan Hari Ini</p>
                                <p class="text-lg font-bold text-yellow-600 mt-1">Rp {{ number_format($pendapatan, 0, ',', '.') }}</p>
                            </div>
                            <div class="w-10 h-10 bg-yellow-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-money-bill text-yellow-600"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel Data Parkir --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center gap-3">
                        <div>
                            <h3 class="font-semibold text-gray-800">Data Kendaraan</h3>
                            <p class="text-xs text-gray-400 mt-0.5">Semua riwayat parkir</p>
                        </div>
                        <div class="sm:ml-auto flex items-center gap-2">
                            <div class="relative">
                                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                                <input type="text" id="searchInput" oninput="filterTable()" placeholder="Cari no. kendaraan..."
                                    class="pl-8 pr-4 py-2 border border-gray-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-300 w-48">
                            </div>
                            <select id="filterStatus" onchange="filterTable()"
                                class="border border-gray-200 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-blue-300 bg-white">
                                <option value="">Semua Status</option>
                                <option value="parkir">Parkir</option>
                                <option value="keluar">Keluar</option>
                            </select>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm" id="dataTable">
                            <thead>
                                <tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider text-left">
                                    <th class="px-4 py-3">ID</th>
                                    <th class="px-4 py-3">Ticket Code</th>
                                    <th class="px-4 py-3">No. Kendaraan</th>
                                    <th class="px-4 py-3">Jenis</th>
                                    <th class="px-4 py-3">Waktu Masuk</th>
                                    <th class="px-4 py-3">Waktu Keluar</th>
                                    <th class="px-4 py-3">Status</th>
                                    <th class="px-4 py-3">Tarif</th>
                                    <th class="px-4 py-3">Petugas</th>
                                    <th class="px-4 py-3 text-center">Karcis</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" id="tableBody">
                                @forelse($parkings as $p)
                                <tr class="hover:bg-gray-50 transition data-row" data-status="{{ $p->status }}">
                                    <td class="px-4 py-3 text-gray-400 text-xs font-mono">{{ $p->id }}</td>
                                    <td class="px-4 py-3">
                                        <span class="font-mono text-xs text-gray-600 bg-gray-100 px-2 py-1 rounded-lg">{{ $p->ticket_code }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="font-bold text-gray-800 tracking-wider text-sm no-kendaraan">{{ $p->no_kendaraan }}</span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @php $icon = match($p->jenis_kendaraan){'mobil'=>'fa-car','truk'=>'fa-truck',default=>'fa-motorcycle'}; $color = match($p->jenis_kendaraan){'mobil'=>'text-blue-500','truk'=>'text-orange-500',default=>'text-indigo-500'}; @endphp
                                        <span class="capitalize {{ $color }} text-xs font-medium"><i class="fas {{ $icon }} mr-1"></i>{{ $p->jenis_kendaraan }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-600">
                                        <div>{{ $p->waktu_masuk->format('d/m/Y') }}</div>
                                        <div class="text-gray-400 font-medium">{{ $p->waktu_masuk->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-600">
                                        @if($p->waktu_keluar)
                                            <div>{{ $p->waktu_keluar->format('d/m/Y') }}</div>
                                            <div class="text-gray-400 font-medium">{{ $p->waktu_keluar->format('H:i') }} WIB</div>
                                        @else
                                            <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($p->status === 'parkir')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span> Parkir
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 bg-gray-100 text-gray-600 rounded-full text-xs font-semibold">Keluar</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs font-semibold text-emerald-600">
                                        @if($p->tarif) Rp {{ number_format($p->tarif, 0, ',', '.') }}
                                        @else <span class="text-gray-300">—</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs text-gray-500">{{ $p->petugas?->name ?? '—' }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <button onclick="openKarcisModal('{{ route('petugas.karcis', $p->ticket_code) }}')"
                                            class="inline-flex items-center justify-center w-7 h-7 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg transition" title="Cetak Karcis">
                                            <i class="fas fa-print text-xs"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="10" class="px-4 py-12 text-center text-gray-400">
                                    <i class="fas fa-database text-3xl mb-2 block opacity-30"></i>
                                    <p class="text-sm">Belum ada data parkir</p>
                                </td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    @if($parkings->hasPages())
                    <div class="px-5 py-4 border-t border-gray-100">
                        {{ $parkings->links() }}
                    </div>
                    @endif
                </div>

            </div>
        </main>
    </div>
    @include('petugas.partials.footer')

    {{-- ===== MODAL POPUP KARCIS ===== --}}
    <div id="karcisModal" style="display:none;position:fixed;inset:0;z-index:999;background:rgba(0,0,0,0.6);align-items:center;justify-content:center;padding:16px;">
        <div style="background:white;border-radius:20px;overflow:hidden;box-shadow:0 30px 80px rgba(0,0,0,0.3);width:100%;max-width:380px;animation:modalPop 0.3s cubic-bezier(0.34,1.56,0.64,1)">
            <style>
                @keyframes modalPop { from{opacity:0;transform:scale(0.85)}to{opacity:1;transform:scale(1)} }
            </style>
            {{-- Top Bar --}}
            <div style="background:linear-gradient(135deg,#1d4ed8,#4f46e5);padding:14px 18px;display:flex;align-items:center;justify-content:space-between">
                <div style="display:flex;align-items:center;gap:8px;color:white">
                    <i class="fas fa-ticket-alt"></i>
                    <span style="font-weight:700;font-size:14px">Preview Karcis</span>
                </div>
                <button onclick="closeKarcisModal()" style="color:rgba(255,255,255,0.7);font-size:18px;line-height:1;background:none;border:none;cursor:pointer;padding:2px 6px;" onmouseover="this.style.color='white'" onmouseout="this.style.color='rgba(255,255,255,0.7)'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            {{-- Iframe --}}
            <div style="background:#f0f4f8;display:flex;justify-content:center;overflow-y:auto;max-height:65vh">
                <iframe id="karcisIframe" src="" scrolling="auto" style="border:none;width:340px;height:560px;background:#f0f4f8"></iframe>
            </div>
            {{-- Actions --}}
            <div style="padding:14px 18px;display:flex;gap:10px;border-top:1px solid #e2e8f0;background:white">
                <button onclick="cetakKarcis()"
                    style="flex:1;background:#2563eb;color:white;font-weight:700;padding:10px;border-radius:12px;font-size:13px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;box-shadow:0 4px 12px rgba(37,99,235,0.3)"
                    onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='#2563eb'">
                    <i class="fas fa-print"></i> Cetak Karcis
                </button>
                <button onclick="closeKarcisModal()"
                    style="flex:1;border:2px solid #e2e8f0;color:#4b5563;font-weight:600;padding:10px;border-radius:12px;font-size:13px;background:white;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px"
                    onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background='white'">
                    <i class="fas fa-times"></i> Tutup
                </button>
            </div>
        </div>
    </div>
    <script>
        const sidebar=document.getElementById('sidebar'),toggleBtn=document.getElementById('sidebarToggle'),overlay=document.getElementById('overlay');
        function closeSidebar(){sidebar.classList.remove('open');overlay.classList.remove('active');}
        function openSidebar(){sidebar.classList.add('open');overlay.classList.add('active');}
        toggleBtn.addEventListener('click',e=>{e.stopPropagation();sidebar.classList.contains('open')?closeSidebar():openSidebar();});
        overlay.addEventListener('click',closeSidebar);
        window.addEventListener('resize',()=>{if(window.innerWidth>768)closeSidebar();});

        function filterTable(){
            const q = document.getElementById('searchInput').value.toLowerCase();
            const s = document.getElementById('filterStatus').value.toLowerCase();
            document.querySelectorAll('.data-row').forEach(row => {
                const no = row.querySelector('.no-kendaraan')?.textContent.toLowerCase() || '';
                const st = row.dataset.status || '';
                row.style.display = (no.includes(q) && (s===''||st===s)) ? '' : 'none';
            });
        }

        // Modal karcis
        function openKarcisModal(url) {
            document.getElementById('karcisIframe').src = url;
            const modal = document.getElementById('karcisModal');
            modal.style.display = 'flex';
        }
        function closeKarcisModal() {
            document.getElementById('karcisModal').style.display = 'none';
            document.getElementById('karcisIframe').src = '';
        }
        function cetakKarcis() {
            const iframe = document.getElementById('karcisIframe');
            if (iframe && iframe.contentWindow) iframe.contentWindow.print();
        }
        document.getElementById('karcisModal').addEventListener('click', function(e) {
            if (e.target === this) closeKarcisModal();
        });
    </script>
</body>
</html>