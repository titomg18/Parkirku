{{-- resources/views/petugas/partials/navbar.blade.php --}}
<nav class="bg-white/80 backdrop-blur-md border-b border-blue-100 fixed top-0 left-0 right-0 z-40 shadow-sm">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <button id="sidebarToggle" class="text-gray-600 hover:text-blue-600 focus:outline-none lg:hidden mr-3 transition">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <div class="flex items-center space-x-2">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-2 rounded-xl shadow-md">
                        <i class="fas fa-parking text-white text-lg"></i>
                    </div>
                    <div>
                        <span class="font-extrabold text-gray-800 text-xl tracking-tight">ParkirKu</span>
                        <span class="hidden sm:inline ml-2 text-xs bg-blue-100 text-blue-600 font-semibold px-2 py-0.5 rounded-full">Petugas</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-blue-500 font-medium">Petugas Parkir</p>
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