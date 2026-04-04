{{-- resources/views/petugas/masuk.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kendaraan Masuk - ParkirKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Courier+Prime:wght@700&display=swap" rel="stylesheet">
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
        .plat-input { font-family: 'Courier Prime', monospace; text-transform: uppercase; letter-spacing: 0.12em; }

        /* ===== MODAL KARCIS ===== */
        #karcisModal {
            display: none;
            position: fixed; inset: 0; z-index: 999;
            background: rgba(0,0,0,0.6);
            align-items: center; justify-content: center;
            padding: 16px;
        }
        #karcisModal.show { display: flex; }
        .modal-box {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 30px 80px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 380px;
            animation: modalPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        @keyframes modalPop { from { opacity:0; transform:scale(0.85); } to { opacity:1; transform:scale(1); } }
        .modal-topbar {
            background: linear-gradient(135deg, #1d4ed8 0%, #4f46e5 100%);
            padding: 14px 18px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .modal-iframe-wrap {
            background: #f0f4f8;
            display: flex; justify-content: center;
            padding: 0;
            overflow-y: auto;
            max-height: 65vh;
        }
        .modal-iframe-wrap iframe {
            border: none;
            width: 340px;
            height: 560px;
            background: #f0f4f8;
        }
        .modal-actions {
            padding: 14px 18px;
            display: flex; gap: 10px;
            border-top: 1px solid #e2e8f0;
            background: white;
        }
        @media print { #karcisModal { display: none !important; } }
    </style>
</head>
<body>
    @include('petugas.partials.navbar')
    <div id="overlay" class="overlay fixed inset-0 bg-black/50 z-50 lg:hidden"></div>
    <div class="flex pt-16 min-h-screen">
        @include('petugas.partials.sidebar')
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="animate-fade-up max-w-lg mx-auto">

                {{-- Header --}}
                <div class="mb-6">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Kendaraan Masuk</h1>
                    <p class="text-gray-500 text-sm mt-1">Input nomor kendaraan untuk cetak karcis</p>
                </div>

                {{-- Form Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-5">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <i class="fas fa-sign-in-alt text-white text-lg"></i>
                            </div>
                            <div>
                                <h2 class="font-bold text-white text-base">Form Kendaraan Masuk</h2>
                                <p class="text-blue-100 text-xs">{{ now()->format('l, d F Y · H:i') }} WIB</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('petugas.masuk') }}" method="POST" class="p-6 space-y-5">
                        @csrf

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Plat Kendaraan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="no_kendaraan" id="noKendaraan"
                                value="{{ old('no_kendaraan') }}"
                                placeholder="Contoh: B 1234 CD"
                                class="plat-input w-full border-2 border-gray-200 rounded-xl px-4 py-3.5 text-xl font-bold focus:outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-50 @error('no_kendaraan') border-red-400 @enderror"
                                autocomplete="off" maxlength="12" autofocus>
                            @error('no_kendaraan')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Jenis Kendaraan <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="jenis_kendaraan" value="motor"
                                        {{ old('jenis_kendaraan','motor')==='motor'?'checked':'' }} class="sr-only peer">
                                    <div class="peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 peer-checked:shadow-lg peer-checked:shadow-blue-200 border-2 border-gray-200 rounded-xl p-4 text-center transition-all hover:border-blue-300 hover:bg-blue-50">
                                        <i class="fas fa-motorcycle text-2xl block mb-2"></i>
                                        <p class="text-sm font-bold">Motor</p>
                                        <p class="text-xs opacity-60 mt-1">Rp 2.000/jam</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="jenis_kendaraan" value="mobil"
                                        {{ old('jenis_kendaraan')==='mobil'?'checked':'' }} class="sr-only peer">
                                    <div class="peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 peer-checked:shadow-lg peer-checked:shadow-blue-200 border-2 border-gray-200 rounded-xl p-4 text-center transition-all hover:border-blue-300 hover:bg-blue-50">
                                        <i class="fas fa-car text-2xl block mb-2"></i>
                                        <p class="text-sm font-bold">Mobil</p>
                                        <p class="text-xs opacity-60 mt-1">Rp 5.000/jam</p>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="jenis_kendaraan" value="truk"
                                        {{ old('jenis_kendaraan')==='truk'?'checked':'' }} class="sr-only peer">
                                    <div class="peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 peer-checked:shadow-lg peer-checked:shadow-blue-200 border-2 border-gray-200 rounded-xl p-4 text-center transition-all hover:border-blue-300 hover:bg-blue-50">
                                        <i class="fas fa-truck text-2xl block mb-2"></i>
                                        <p class="text-sm font-bold">Truk</p>
                                        <p class="text-xs opacity-60 mt-1">Rp 10.000/jam</p>
                                    </div>
                                </label>
                            </div>
                            @error('jenis_kendaraan')
                                <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 flex items-center gap-3">
                            <i class="fas fa-clock text-blue-400"></i>
                            <div>
                                <p class="text-xs text-blue-500 font-medium">Waktu masuk akan dicatat otomatis</p>
                                <p class="text-xs text-blue-400" id="jamSekarang"></p>
                            </div>
                        </div>

                        <div class="flex gap-3 pt-1">
                            <a href="{{ route('petugas.dashboard') }}"
                                class="flex-1 border-2 border-gray-200 text-gray-600 hover:bg-gray-50 font-semibold py-3 rounded-xl text-sm transition text-center">
                                <i class="fas fa-arrow-left mr-1"></i> Batal
                            </a>
                            <button type="submit"
                                class="flex-1 bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-bold py-3 rounded-xl transition shadow-md shadow-blue-200 flex items-center justify-center gap-2">
                                <i class="fas fa-ticket-alt"></i> Cetak Karcis
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </main>
    </div>
    @include('petugas.partials.footer')

    {{-- ===== MODAL POPUP KARCIS ===== --}}
    <div id="karcisModal" @if(session('show_karcis')) class="show" @endif>
        <div class="modal-box">
            {{-- Top Bar --}}
            <div class="modal-topbar">
                <div class="flex items-center gap-2 text-white">
                    <i class="fas fa-ticket-alt"></i>
                    <span class="font-bold text-sm">Karcis Berhasil Dibuat!</span>
                </div>
                <button onclick="closeKarcisModal()" class="text-white/70 hover:text-white transition text-lg leading-none">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- Iframe karcis --}}
            <div class="modal-iframe-wrap">
                <iframe
                    id="karcisIframe"
                    @if(session('show_karcis'))
                        src="{{ route('petugas.karcis', session('show_karcis')) }}"
                    @endif
                    scrolling="auto"
                ></iframe>
            </div>

            {{-- Action buttons --}}
            <div class="modal-actions">
                <button onclick="cetakKarcis()"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl text-sm transition flex items-center justify-center gap-2 shadow shadow-blue-200">
                    <i class="fas fa-print"></i> Cetak Karcis
                </button>
                <button onclick="closeKarcisModal()"
                    class="flex-1 border-2 border-gray-200 text-gray-600 hover:bg-gray-50 font-semibold py-2.5 rounded-xl text-sm transition flex items-center justify-center gap-2">
                    <i class="fas fa-check"></i> Selesai
                </button>
            </div>
        </div>
    </div>

    <script>
        // Sidebar
        const sidebar=document.getElementById('sidebar'),toggleBtn=document.getElementById('sidebarToggle'),overlay=document.getElementById('overlay');
        function closeSidebar(){sidebar.classList.remove('open');overlay.classList.remove('active');}
        function openSidebar(){sidebar.classList.add('open');overlay.classList.add('active');}
        toggleBtn.addEventListener('click',e=>{e.stopPropagation();sidebar.classList.contains('open')?closeSidebar():openSidebar();});
        overlay.addEventListener('click',closeSidebar);
        window.addEventListener('resize',()=>{if(window.innerWidth>768)closeSidebar();});

        // Jam realtime
        function updateJam() {
            const now = new Date();
            document.getElementById('jamSekarang').textContent =
                now.toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'}) + ' · ' +
                now.toLocaleTimeString('id-ID') + ' WIB';
        }
        updateJam(); setInterval(updateJam, 1000);

        // Auto uppercase
        document.getElementById('noKendaraan').addEventListener('input', function(){
            const pos = this.selectionStart;
            this.value = this.value.toUpperCase();
            this.setSelectionRange(pos, pos);
        });

        // Modal functions
        function closeKarcisModal() {
            document.getElementById('karcisModal').classList.remove('show');
        }

        function cetakKarcis() {
            const iframe = document.getElementById('karcisIframe');
            if (iframe && iframe.contentWindow) {
                iframe.contentWindow.print();
            }
        }

        // Tutup modal jika klik backdrop
        document.getElementById('karcisModal').addEventListener('click', function(e) {
            if (e.target === this) closeKarcisModal();
        });
    </script>
</body>
</html>