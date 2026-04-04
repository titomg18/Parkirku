{{-- resources/views/admin/tarif.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarif Parkir - ParkirKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: linear-gradient(145deg, #f0f4f8 0%, #e8edf2 100%); }
        @keyframes fadeInUp { from{opacity:0;transform:translateY(16px)}to{opacity:1;transform:translateY(0)} }
        .fade-up { animation: fadeInUp 0.4s ease-out forwards; }
        .overlay { transition:opacity 0.3s; opacity:0; visibility:hidden; }
        .overlay.active { opacity:1; visibility:visible; }
        @media(max-width:768px){ .sidebar{transform:translateX(-100%);position:fixed;z-index:100;top:0;left:0;width:280px;height:100vh;} .sidebar.open{transform:translateX(0);} }
        .tarif-card { transition: all 0.25s; }
        .tarif-card:hover { transform: translateY(-2px); }
        .price-input { font-size: 18px; font-weight: 700; }
    </style>
</head>
<body>
@include('admin.partials.navbar')
<div id="overlay" class="overlay fixed inset-0 bg-black/50 z-50 lg:hidden"></div>
<div class="flex pt-16 min-h-screen">
    @include('admin.partials.sidebar')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
        <div class="fade-up max-w-5xl mx-auto">

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Tarif Parkir</h1>
                <p class="text-gray-500 text-sm mt-0.5">Kelola tarif per jam dan tarif inap untuk setiap jenis kendaraan</p>
            </div>

            {{-- Alert --}}
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-700 rounded-xl px-4 py-3 mb-5 flex items-center gap-3 text-sm">
                <i class="fas fa-check-circle text-emerald-500"></i>
                {{ session('success') }}
            </div>
            @endif

            {{-- Info Box Inap --}}
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-2xl p-5 mb-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-600"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-blue-800 mb-2">Cara Kerja Tarif Otomatis</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm text-blue-700">
                            <div class="flex items-start gap-2">
                                <i class="fas fa-clock text-blue-500 mt-0.5 flex-shrink-0"></i>
                                <span><strong>Tarif Per Jam:</strong> Dihitung per jam (dibulatkan ke atas, minimum 1 jam) untuk parkir hari yang sama.</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fas fa-moon text-blue-500 mt-0.5 flex-shrink-0"></i>
                                <span><strong>Tarif Inap:</strong> Berlaku otomatis ketika kendaraan melewati tengah malam (00:00). Dihitung per malam × tarif inap.</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fas fa-lightbulb text-yellow-500 mt-0.5 flex-shrink-0"></i>
                                <span><strong>Contoh:</strong> Motor masuk pkl 22:00, keluar 02:00 esok hari = <strong>1 malam × tarif inap</strong>.</span>
                            </div>
                            <div class="flex items-start gap-2">
                                <i class="fas fa-lightbulb text-yellow-500 mt-0.5 flex-shrink-0"></i>
                                <span><strong>Contoh regular:</strong> Motor masuk pkl 10:00, keluar 13:30 = <strong>4 jam × tarif/jam</strong>.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tarif Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
                @php
                    $cfg = [
                        'motor' => ['icon'=>'fa-motorcycle','label'=>'Motor','gradient'=>'from-indigo-500 to-indigo-700','bg'=>'bg-indigo-50','border'=>'border-indigo-200','text'=>'text-indigo-700','ring'=>'focus:ring-indigo-300 focus:border-indigo-400'],
                        'mobil' => ['icon'=>'fa-car',        'label'=>'Mobil', 'gradient'=>'from-emerald-500 to-emerald-700','bg'=>'bg-emerald-50','border'=>'border-emerald-200','text'=>'text-emerald-700','ring'=>'focus:ring-emerald-300 focus:border-emerald-400'],
                        'truk'  => ['icon'=>'fa-truck',      'label'=>'Truk',  'gradient'=>'from-orange-500 to-orange-700', 'bg'=>'bg-orange-50', 'border'=>'border-orange-200', 'text'=>'text-orange-700', 'ring'=>'focus:ring-orange-300 focus:border-orange-400'],
                    ];
                @endphp

                @foreach($tarifs as $t)
                @php $c = $cfg[$t->jenis_kendaraan] ?? $cfg['motor']; @endphp
                <div class="tarif-card bg-white rounded-2xl shadow-sm border {{ $c['border'] }} overflow-hidden">

                    {{-- Card Header --}}
                    <div class="bg-gradient-to-br {{ $c['gradient'] }} p-5 text-white">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center">
                                <i class="fas {{ $c['icon'] }} text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold">{{ $c['label'] }}</h3>
                                <p class="text-white/70 text-xs">Jenis kendaraan: {{ strtoupper($t->jenis_kendaraan) }}</p>
                            </div>
                        </div>

                        {{-- Preview harga saat ini --}}
                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <div class="bg-white/15 rounded-xl p-3">
                                <p class="text-white/70 text-xs mb-1">Per Jam</p>
                                <p class="text-white font-bold text-lg" id="preview_jam_{{ $t->id }}">Rp {{ number_format($t->tarif_per_jam,0,',','.') }}</p>
                            </div>
                            <div class="bg-white/15 rounded-xl p-3">
                                <p class="text-white/70 text-xs mb-1">Per Malam</p>
                                <p class="text-white font-bold text-lg" id="preview_inap_{{ $t->id }}">Rp {{ number_format($t->tarif_inap,0,',','.') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Form Edit --}}
                    <form action="{{ route('admin.tarif.update', $t->id) }}" method="POST" class="p-5 space-y-4">
                        @csrf @method('PUT')

                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-1.5">
                                <i class="fas fa-clock mr-1 {{ $c['text'] }}"></i>Tarif Per Jam (Rp)
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-semibold">Rp</span>
                                <input type="number" name="tarif_per_jam" value="{{ $t->tarif_per_jam }}"
                                    min="0" step="500"
                                    id="input_jam_{{ $t->id }}"
                                    oninput="updatePreview('{{ $t->id }}')"
                                    class="price-input w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl {{ $c['ring'] }} focus:outline-none transition text-gray-800">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Berlaku untuk parkir hari yang sama (dibulatkan ke atas per jam)</p>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-600 uppercase tracking-wide mb-1.5">
                                <i class="fas fa-moon mr-1 {{ $c['text'] }}"></i>Tarif Inap Per Malam (Rp)
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm font-semibold">Rp</span>
                                <input type="number" name="tarif_inap" value="{{ $t->tarif_inap }}"
                                    min="0" step="500"
                                    id="input_inap_{{ $t->id }}"
                                    oninput="updatePreview('{{ $t->id }}')"
                                    class="price-input w-full pl-10 pr-4 py-3 border-2 border-gray-200 rounded-xl {{ $c['ring'] }} focus:outline-none transition text-gray-800">
                            </div>
                            <p class="text-xs text-gray-400 mt-1">Berlaku otomatis saat kendaraan melewati 00:00 (per malam)</p>
                        </div>

                        <button type="submit"
                            class="w-full bg-gradient-to-r {{ $c['gradient'] }} text-white font-bold py-3 rounded-xl hover:opacity-90 active:scale-95 transition shadow-sm flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i> Simpan Tarif
                        </button>
                    </form>
                </div>
                @endforeach
            </div>

            {{-- Simulasi Tarif --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-calculator text-purple-500"></i>
                    Simulasi Perhitungan Tarif
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Jenis Kendaraan</label>
                        <select id="sim_jenis" onchange="simulasi()" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-300 bg-white">
                            @foreach($tarifs as $t)
                            <option value="{{ $t->tarif_per_jam }}" data-inap="{{ $t->tarif_inap }}" data-nama="{{ $t->jenis_kendaraan }}">{{ ucfirst($t->jenis_kendaraan) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Waktu Masuk</label>
                        <input type="datetime-local" id="sim_masuk" onchange="simulasi()" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-300">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Waktu Keluar</label>
                        <input type="datetime-local" id="sim_keluar" onchange="simulasi()" class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-300">
                    </div>
                    <div id="sim_hasil" class="bg-purple-50 border border-purple-200 rounded-xl p-3 flex flex-col justify-center">
                        <p class="text-xs text-purple-500 font-semibold uppercase tracking-wide mb-1">Estimasi Tarif</p>
                        <p class="text-2xl font-bold text-purple-700" id="sim_tarif">—</p>
                        <p class="text-xs text-purple-400 mt-1" id="sim_keterangan">Isi form di kiri</p>
                    </div>
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

    function formatRp(n) {
        return 'Rp ' + n.toLocaleString('id-ID');
    }

    function updatePreview(id) {
        const jam  = parseInt(document.getElementById('input_jam_'+id).value)||0;
        const inap = parseInt(document.getElementById('input_inap_'+id).value)||0;
        document.getElementById('preview_jam_'+id).textContent  = formatRp(jam);
        document.getElementById('preview_inap_'+id).textContent = formatRp(inap);
    }

    // Simulasi
    function simulasi() {
        const masuk  = new Date(document.getElementById('sim_masuk').value);
        const keluar = new Date(document.getElementById('sim_keluar').value);
        const sel    = document.getElementById('sim_jenis');
        const tarifJam  = parseInt(sel.value)||0;
        const tarifInap = parseInt(sel.options[sel.selectedIndex].dataset.inap)||0;
        const jenis  = sel.options[sel.selectedIndex].dataset.nama;

        if (!masuk || !keluar || isNaN(masuk) || isNaN(keluar) || keluar <= masuk) {
            document.getElementById('sim_tarif').textContent = '—';
            document.getElementById('sim_keterangan').textContent = 'Isi waktu masuk & keluar';
            return;
        }

        const msMasuk  = new Date(masuk.getFullYear(), masuk.getMonth(), masuk.getDate());
        const msKeluar = new Date(keluar.getFullYear(), keluar.getMonth(), keluar.getDate());
        const jumlahMalam = Math.round((msKeluar - msMasuk) / 86400000);

        let total, ket;
        if (jumlahMalam >= 1) {
            total = jumlahMalam * tarifInap;
            ket = jumlahMalam + ' malam × ' + formatRp(tarifInap) + ' (tarif inap)';
        } else {
            const menit = Math.ceil((keluar - masuk) / 60000);
            const jam   = Math.max(1, Math.ceil(menit / 60));
            total = jam * tarifJam;
            ket = jam + ' jam × ' + formatRp(tarifJam) + ' (tarif regular)';
        }

        document.getElementById('sim_tarif').textContent = formatRp(total);
        document.getElementById('sim_keterangan').textContent = ket;
    }

    // Set default waktu sekarang
    const now = new Date();
    const pad = n => String(n).padStart(2,'0');
    const fmt = d => d.getFullYear()+'-'+pad(d.getMonth()+1)+'-'+pad(d.getDate())+'T'+pad(d.getHours())+':'+pad(d.getMinutes());
    document.getElementById('sim_masuk').value = fmt(now);
    document.getElementById('sim_keluar').value = fmt(new Date(now.getTime()+3600000));
</script>
</body>
</html>