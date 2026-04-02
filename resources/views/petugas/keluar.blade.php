{{-- resources/views/petugas/keluar.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kendaraan Keluar - ParkirKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Courier+Prime:wght@700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/html5-qrcode@2.3.7/minified/html5-qrcode.min.js" onerror="(function(){var s=document.createElement('script');s.src='https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.7/minified/html5-qrcode.min.js';document.head.appendChild(s);})();"></script>
    <script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.7/html5-qrcode.min.js"></script>
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
        .scan-input { font-family: 'Courier Prime', monospace; font-size: 18px; letter-spacing: 0.08em; text-transform: uppercase; }
        @keyframes successPop { 0%{transform:scale(0.8);opacity:0} 70%{transform:scale(1.05)} 100%{transform:scale(1);opacity:1} }
        .success-pop { animation: successPop 0.4s ease forwards; }
    </style>
</head>
<body>
    @include('petugas.partials.navbar')
    <div id="overlay" class="overlay fixed inset-0 bg-black/50 z-50 lg:hidden"></div>
    <div class="flex pt-16 min-h-screen">
        @include('petugas.partials.sidebar')

        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="animate-fade-up">

                <div class="mb-6">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Kendaraan Keluar</h1>
                    <p class="text-gray-500 text-sm mt-1">Scan barcode atau input kode karcis secara manual</p>
                </div>

                {{-- Alert Sukses Keluar --}}
                @if(session('sukses_keluar'))
                @php
                    $p = \App\Models\Parking::where('ticket_code', session('sukses_keluar'))->first();
                @endphp
                @if($p)
                <div class="success-pop bg-emerald-50 border-2 border-emerald-300 rounded-2xl p-5 mb-6 flex items-start gap-4">
                    <div class="w-12 h-12 bg-emerald-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-white text-xl"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold text-emerald-700 text-lg">Kendaraan Berhasil Keluar!</p>
                        <p class="text-emerald-600 font-mono text-sm mt-1">{{ $p->no_kendaraan }} — {{ strtoupper($p->jenis_kendaraan) }}</p>
                        <div class="mt-3 grid grid-cols-3 gap-3">
                            <div class="bg-white rounded-xl p-3 text-center border border-emerald-200">
                                <p class="text-xs text-gray-500">Masuk</p>
                                <p class="font-bold text-gray-800 text-sm">{{ $p->waktu_masuk->format('H:i') }}</p>
                            </div>
                            <div class="bg-white rounded-xl p-3 text-center border border-emerald-200">
                                <p class="text-xs text-gray-500">Durasi</p>
                                <p class="font-bold text-gray-800 text-sm">{{ $p->durasi }} mnt</p>
                            </div>
                            <div class="bg-white rounded-xl p-3 text-center border border-emerald-200">
                                <p class="text-xs text-gray-500">Total</p>
                                <p class="font-bold text-emerald-600 text-sm">Rp {{ number_format($p->tarif, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @endif

                {{-- Alert Error --}}
                @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 flex items-center gap-3 text-sm">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">

                    {{-- Form Input Kode --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-orange-500 to-red-500 p-4 sm:p-5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-qrcode text-white text-lg"></i>
                                    </div>
                                    <div>
                                        <h2 class="font-bold text-white">Scan / Input Karcis</h2>
                                        <p class="text-orange-100 text-xs">Arahkan scanner ke barcode karcis</p>
                                    </div>
                                </div>
                            </div>
                            <div class="p-5">
                                {{-- Scan indicator --}}
                                <div class="flex items-center gap-2 mb-4 p-3 bg-orange-50 rounded-xl border border-orange-100">
                                    <div class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></div>
                                    <p class="text-xs text-orange-600 font-medium">Siap menerima scan barcode...</p>
                                </div>

                                <form action="{{ route('petugas.keluar.proses') }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Kode Karcis</label>
                                        <input type="text" name="ticket_code" id="ticketInput"
                                            placeholder="Scan atau ketik kode..."
                                            class="scan-input w-full border-2 border-gray-200 rounded-xl px-4 py-3 focus:outline-none focus:border-orange-400 focus:ring-2 focus:ring-orange-100"
                                            autocomplete="off" autofocus>
                                        <p class="text-xs text-gray-400 mt-1.5">Contoh: PKR-20250403-AB123</p>
                                    </div>
                                    <button type="submit"
                                        class="w-full bg-orange-500 hover:bg-orange-600 active:scale-95 text-white font-bold py-3 rounded-xl transition shadow flex items-center justify-center gap-2">
                                        <i class="fas fa-sign-out-alt"></i> Proses Keluar
                                    </button>
                                </form>

                                <div class="mt-4 border-t pt-4">
                                    <button id="startCameraBtn" type="button"
                                        class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 rounded-xl transition shadow flex items-center justify-center gap-2">
                                        <i class="fas fa-camera"></i> Scan dengan Kamera
                                    </button>
                                    <p id="cameraStatus" class="text-xs text-gray-500 mt-2">Tekan tombol untuk membuka kamera.</p>
                                    <div id="qr-reader" class="mt-3" style="width:100%; display:none;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Riwayat Keluar Hari Ini --}}
                    <div class="lg:col-span-3">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="p-4 sm:p-5 border-b border-gray-100 flex items-center justify-between">
                                <h3 class="font-semibold text-gray-800">Riwayat Keluar Hari Ini</h3>
                                <span class="px-2.5 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">{{ $riwayat->count() }} terakhir</span>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm">
                                    <thead><tr class="bg-gray-50 text-xs font-semibold text-gray-500 uppercase tracking-wider text-left">
                                        <th class="px-4 py-3">No. Kendaraan</th>
                                        <th class="px-4 py-3">Jenis</th>
                                        <th class="px-4 py-3">Masuk</th>
                                        <th class="px-4 py-3">Keluar</th>
                                        <th class="px-4 py-3">Total</th>
                                    </tr></thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse($riwayat as $p)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3"><span class="font-bold text-gray-800 tracking-wider text-xs">{{ $p->no_kendaraan }}</span></td>
                                            <td class="px-4 py-3">
                                                @php $icon = match($p->jenis_kendaraan){'mobil'=>'fa-car','truk'=>'fa-truck',default=>'fa-motorcycle'}; @endphp
                                                <i class="fas {{ $icon }} text-gray-400"></i>
                                            </td>
                                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $p->waktu_masuk->format('H:i') }}</td>
                                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $p->waktu_keluar?->format('H:i') ?? '-' }}</td>
                                            <td class="px-4 py-3">
                                                <span class="text-xs font-semibold text-emerald-600">
                                                    Rp {{ number_format($p->tarif ?? 0, 0, ',', '.') }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="5" class="px-4 py-10 text-center text-gray-400">
                                            <i class="fas fa-history text-3xl mb-2 block opacity-30"></i>
                                            <p class="text-sm">Belum ada riwayat keluar hari ini</p>
                                        </td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
    @include('petugas.partials.footer')
    <script>
        const sidebar=document.getElementById('sidebar'),toggleBtn=document.getElementById('sidebarToggle'),overlay=document.getElementById('overlay');
        function closeSidebar(){sidebar.classList.remove('open');overlay.classList.remove('active');}
        function openSidebar(){sidebar.classList.add('open');overlay.classList.add('active');}
        toggleBtn.addEventListener('click',e=>{e.stopPropagation();sidebar.classList.contains('open')?closeSidebar():openSidebar();});
        overlay.addEventListener('click',closeSidebar);
        window.addEventListener('resize',()=>{if(window.innerWidth>768)closeSidebar();});

        // Auto focus & auto submit saat scanner mengirim Enter
        document.getElementById('ticketInput').focus();
        document.getElementById('ticketInput').addEventListener('keydown', function(e){
            if(e.key === 'Enter'){
                if(this.value.trim().length > 0){
                    this.closest('form').submit();
                }
            }
        });

        const startCameraBtn = document.getElementById('startCameraBtn');
        const cameraStatus = document.getElementById('cameraStatus');
        const qrReader = document.getElementById('qr-reader');
        let html5QrcodeScanner;

        startCameraBtn.addEventListener('click', async function(){
            if(qrReader.style.display === 'none'){
                cameraStatus.textContent = 'Mencari kamera...';
                qrReader.style.display = 'block';

                if(typeof Html5Qrcode === 'undefined'){
                    cameraStatus.textContent = 'Gagal mengakses kamera: library Html5Qrcode tidak tersedia. Coba refresh halaman atau cek koneksi internet.';
                    qrReader.style.display = 'none';
                    return;
                }

                try {
                    html5QrcodeScanner = new Html5Qrcode('qr-reader');
                    const config = { fps: 10, qrbox: { width: 280, height: 180 } };

                    await html5QrcodeScanner.start(
                        { facingMode: 'environment' },
                        config,
                        decodedText => {
                            document.getElementById('ticketInput').value = decodedText.trim();
                            cameraStatus.textContent = 'Kode terdeteksi: ' + decodedText;
                            html5QrcodeScanner.stop().catch(() => {});
                            qrReader.style.display = 'none';
                            document.getElementById('ticketInput').focus();
                            setTimeout(() => {
                                document.querySelector('form').submit();
                            }, 250);
                        },
                        errorMessage => {
                            cameraStatus.textContent = 'Scan: ' + errorMessage;
                        }
                    );
                    cameraStatus.textContent = 'Kamera aktif - arahkan ke barcode karcis.';
                } catch (err) {
                    console.error(err);
                    cameraStatus.textContent = 'Gagal mengakses kamera: ' + (err.message || err);
                    qrReader.style.display = 'none';
                }
            } else {
                if(html5QrcodeScanner){
                    html5QrcodeScanner.stop().catch(() => {});
                }
                qrReader.style.display = 'none';
                cameraStatus.textContent = 'Kamera dinonaktifkan.';
            }
        });
    </script>
</body>
</html>