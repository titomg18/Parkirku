{{-- resources/views/petugas/partials/sidebar.blade.php --}}
<aside id="sidebar" class="sidebar sidebar-transition bg-white/95 backdrop-blur-sm border-r border-gray-200 w-72 fixed lg:static lg:translate-x-0 overflow-y-auto">
    <div class="p-5 space-y-2">
        {{-- User Profile --}}
        <div class="flex items-center space-x-3 pb-4 mb-2 border-b border-gray-200">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-indigo-500 flex items-center justify-center text-white font-bold text-lg">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <p class="font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                <p class="text-xs text-blue-500 font-medium">Petugas Parkir</p>
            </div>
        </div>

        {{-- Menu --}}
        <nav class="space-y-1">
            <a href="{{ route('petugas.dashboard') }}"
               class="flex items-center space-x-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('petugas.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }} transition group">
                <i class="fas fa-tachometer-alt w-5 {{ request()->routeIs('petugas.dashboard') ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="{{ route('petugas.masuk.index') }}"
               class="flex items-center space-x-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('petugas.masuk.index') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }} transition group">
                <i class="fas fa-sign-in-alt w-5 {{ request()->routeIs('petugas.masuk.index') ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
                <span class="font-medium">Kendaraan Masuk</span>
            </a>
            <a href="{{ route('petugas.keluar') }}"
               class="flex items-center space-x-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('petugas.keluar') ? 'bg-blue-50 text-blue-600' : 'text-gray-700 hover:bg-blue-50 hover:text-blue-600' }} transition group">
                <i class="fas fa-sign-out-alt w-5 {{ request()->routeIs('petugas.keluar') ? 'text-blue-500' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
                <span class="font-medium">Kendaraan Keluar</span>
            </a>
        </nav>

        {{-- Logout --}}
        <div class="pt-6 mt-6 border-t border-gray-200">
            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="w-full flex items-center space-x-3 px-4 py-2.5 rounded-xl text-red-500 hover:bg-red-50 transition">
                    <i class="fas fa-sign-out-alt w-5"></i>
                    <span class="font-medium">Keluar</span>
                </button>
            </form>
        </div>
    </div>
</aside>