{{-- Navbar --}}
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