{{-- resources/views/petugas/dashboard.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Dashboard Petugas - ParkirKu</title>
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- Google Fonts: Inter --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(145deg, #f0f4f8 0%, #e2e8f0 100%);
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        .sidebar-transition {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .overlay {
            transition: opacity 0.3s ease;
            opacity: 0;
            visibility: hidden;
        }
        .overlay.active {
            opacity: 1;
            visibility: visible;
        }
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 100;
                top: 0;
                left: 0;
                width: 280px;
                height: 100vh;
                box-shadow: 2px 0 20px rgba(0,0,0,0.2);
            }
            .sidebar.open {
                transform: translateX(0);
            }
            .overlay {
                z-index: 90;
            }
        }
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body>

    {{-- Navbar (sama seperti admin, bisa dipisah jika ingin) --}}
    <nav class="bg-white/80 backdrop-blur-md border-b border-gray-200/50 fixed top-0 left-0 right-0 z-40 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <button id="sidebarToggle" class="text-gray-600 hover:text-emerald-600 focus:outline-none lg:hidden mr-3 transition">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="flex items-center space-x-2">
                        <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-2 rounded-xl shadow-md">
                            <i class="fas fa-parking text-white text-lg"></i>
                        </div>
                        <span class="font-extrabold text-gray-800 text-xl tracking-tight">ParkirKu</span>
                    </div>
                </div>
                <div class="flex items-center space-x-5">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                    </div>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-1.5 rounded-lg transition transform hover:scale-105 text-sm font-medium flex items-center space-x-1 shadow-sm">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    {{-- Overlay untuk mobile --}}
    <div id="overlay" class="overlay fixed inset-0 bg-black/50 z-50 lg:hidden transition-all duration-300"></div>

    <div class="flex pt-16 min-h-screen">
        {{-- SIDEBAR PETUGAS (hanya Kendaraan Masuk & Keluar) --}}
        <aside id="sidebar" class="sidebar sidebar-transition bg-white/95 backdrop-blur-sm border-r border-gray-200 w-72 fixed lg:static lg:translate-x-0 overflow-y-auto">
            <div class="p-5 space-y-2">
                {{-- User Profile Ringkas --}}
                <div class="flex items-center space-x-3 pb-4 mb-2 border-b border-gray-200">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-lg">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                    </div>
                </div>

                {{-- Menu Petugas --}}
                <nav class="space-y-1">
                    {{-- Dashboard (opsional, bisa diarahkan ke halaman ini) --}}
                    <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition group">
                        <i class="fas fa-tachometer-alt w-5 text-gray-400 group-hover:text-emerald-500"></i>
                        <span class="font-medium">Dashboard</span>
                    </a>

                    {{-- Kendaraan Masuk --}}
                    <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition group">
                        <i class="fas fa-sign-in-alt w-5 text-gray-400 group-hover:text-emerald-500"></i>
                        <span class="font-medium">Kendaraan Masuk</span>
                    </a>

                    {{-- Kendaraan Keluar --}}
                    <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition group">
                        <i class="fas fa-sign-out-alt w-5 text-gray-400 group-hover:text-emerald-500"></i>
                        <span class="font-medium">Kendaraan Keluar</span>
                    </a>
                </nav>

                {{-- Bagian Pengaturan sederhana --}}
                <div class="pt-6 mt-6 border-t border-gray-200">
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">Pengaturan</p>
                    <div class="mt-2 space-y-1">
                        <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition group">
                            <i class="fas fa-user-cog w-5 text-gray-400 group-hover:text-emerald-500"></i>
                            <span class="font-medium">Profil</span>
                        </a>
                    </div>
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="animate-fade-up">
                {{-- Welcome Card --}}
                <div class="bg-white rounded-2xl shadow-md p-5 sm:p-6 mb-6 sm:mb-8 border border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Selamat datang, {{ auth()->user()->name }}!</h1>
                            <p class="text-gray-500 text-sm mt-1">Role: <span class="font-semibold text-emerald-600">{{ auth()->user()->role }}</span></p>
                            <p class="text-gray-400 text-xs sm:text-sm mt-2">Kelola kendaraan masuk dan keluar dengan mudah.</p>
                        </div>
                        <div class="mt-3 sm:mt-0">
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-xs sm:text-sm font-medium">Petugas</span>
                        </div>
                    </div>
                </div>

                {{-- Statistik Sederhana (opsional) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6 mb-6 sm:mb-8">
                    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-5 border border-gray-100 hover:border-emerald-200 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs sm:text-sm font-medium">Kendaraan Masuk (Hari Ini)</p>
                                <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-1">14</p>
                            </div>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-100 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-sign-in-alt text-emerald-600 text-lg sm:text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-5 border border-gray-100 hover:border-blue-200 transition">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-500 text-xs sm:text-sm font-medium">Kendaraan Keluar (Hari Ini)</p>
                                <p class="text-2xl sm:text-3xl font-bold text-gray-800 mt-1">10</p>
                            </div>
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-2xl flex items-center justify-center">
                                <i class="fas fa-sign-out-alt text-blue-600 text-lg sm:text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Aktivitas Terbaru (contoh) --}}
                <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-5 border border-gray-100">
                    <h3 class="font-semibold text-gray-800 mb-4 text-sm sm:text-base">Aktivitas Terbaru</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center"><i class="fas fa-car text-green-600 text-sm"></i></div>
                            <div><p class="text-sm text-gray-800">Kendaraan B 1234 CD masuk pukul 08:30</p><p class="text-xs text-gray-400">10 menit lalu</p></div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center"><i class="fas fa-sign-out-alt text-red-600 text-sm"></i></div>
                            <div><p class="text-sm text-gray-800">Kendaraan D 5678 EF keluar pukul 09:15</p><p class="text-xs text-gray-400">1 jam lalu</p></div>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center"><i class="fas fa-car text-green-600 text-sm"></i></div>
                            <div><p class="text-sm text-gray-800">Kendaraan F 9012 GH masuk pukul 10:45</p><p class="text-xs text-gray-400">2 jam lalu</p></div>
                        </div>
                    </div>
                </div>

                {{-- Menu Cepat (hanya dua menu) --}}
                <h2 class="text-lg sm:text-xl font-bold text-gray-800 mt-6 mb-3 sm:mb-4">Aksi Cepat</h2>
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    <a href="#" class="bg-white rounded-xl p-3 sm:p-4 text-center hover:shadow-md transition">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-emerald-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-sign-in-alt text-emerald-600 text-lg sm:text-xl"></i>
                        </div>
                        <span class="text-gray-700 text-xs sm:text-sm">Kendaraan Masuk</span>
                    </a>
                    <a href="#" class="bg-white rounded-xl p-3 sm:p-4 text-center hover:shadow-md transition">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-2">
                            <i class="fas fa-sign-out-alt text-blue-600 text-lg sm:text-xl"></i>
                        </div>
                        <span class="text-gray-700 text-xs sm:text-sm">Kendaraan Keluar</span>
                    </a>
                </div>
            </div>
        </main>
    </div>

    {{-- Footer --}}
    <footer class="bg-white border-t border-gray-200 py-4 sm:py-5 text-center text-gray-400 text-xs sm:text-sm">
        &copy; {{ date('Y') }} ParkirKu — Sistem Manajemen Parkir Modern
    </footer>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('overlay');

        function closeSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        }
        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('active');
        }
        toggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            if (sidebar.classList.contains('open')) closeSidebar();
            else openSidebar();
        });
        overlay.addEventListener('click', closeSidebar);
        window.addEventListener('resize', () => { if (window.innerWidth > 768) closeSidebar(); });
    </script>
</body>
</html>