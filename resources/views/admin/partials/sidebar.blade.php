{{-- Sidebar --}}
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

        {{-- Menu Utama Admin --}}
        <nav class="space-y-1">
            <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition group">
                <i class="fas fa-tachometer-alt w-5 text-gray-400 group-hover:text-emerald-500"></i>
                <span class="font-medium">Dashboard</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition group">
                <i class="fas fa-database w-5 text-gray-400 group-hover:text-emerald-500"></i>
                <span class="font-medium">Data Kendaraan</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition group">
                <i class="fas fa-sign-in-alt w-5 text-gray-400 group-hover:text-emerald-500"></i>
                <span class="font-medium">Kendaraan Masuk</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition group">
                <i class="fas fa-sign-out-alt w-5 text-gray-400 group-hover:text-emerald-500"></i>
                <span class="font-medium">Kendaraan Keluar</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition group">
                <i class="fas fa-money-bill-wave w-5 text-gray-400 group-hover:text-emerald-500"></i>
                <span class="font-medium">Tarif Parkir</span>
            </a>
            <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition group">
                <i class="fas fa-chart-line w-5 text-gray-400 group-hover:text-emerald-500"></i>
                <span class="font-medium">Laporan</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('admin.users.*') ? 'bg-emerald-50 text-emerald-600' : 'text-gray-700 hover:bg-emerald-50 hover:text-emerald-600' }} transition group">
                <i class="fas fa-users w-5 {{ request()->routeIs('admin.users.*') ? 'text-emerald-500' : 'text-gray-400 group-hover:text-emerald-500' }}"></i>
                <span class="font-medium">Manajemen User</span>
            </a>
        </nav>

        {{-- Separator & Pengaturan --}}
        <div class="pt-6 mt-6 border-t border-gray-200">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-4">Pengaturan</p>
            <div class="mt-2 space-y-1">
                <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition group">
                    <i class="fas fa-user-cog w-5 text-gray-400 group-hover:text-emerald-500"></i>
                    <span class="font-medium">Profil</span>
                </a>
                <a href="#" class="flex items-center space-x-3 px-4 py-2.5 rounded-xl text-gray-700 hover:bg-emerald-50 hover:text-emerald-600 transition group">
                    <i class="fas fa-cog w-5 text-gray-400 group-hover:text-emerald-500"></i>
                    <span class="font-medium">Pengaturan</span>
                </a>
            </div>
        </div>
    </div>
</aside>