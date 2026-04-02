{{-- resources/views/admin/users/index.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Manajemen User - ParkirKu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&display=swap" rel="stylesheet">
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
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        .modal-overlay { transition: opacity 0.2s ease; }
        .modal-box { transition: transform 0.2s ease, opacity 0.2s ease; }
    </style>
</head>
<body>

    {{-- Navbar --}}
    @include('admin.partials.navbar')

    {{-- Overlay mobile --}}
    <div id="overlay" class="overlay fixed inset-0 bg-black/50 z-50 lg:hidden transition-all duration-300"></div>

    <div class="flex pt-16 min-h-screen">
        {{-- Sidebar --}}
        @include('admin.partials.sidebar')

        {{-- Main Content --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <div class="animate-fade-up">

                {{-- Header --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-3">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Manajemen User</h1>
                        <p class="text-gray-500 text-sm mt-1">Kelola akun admin dan petugas parkir</p>
                    </div>
                    <button onclick="openCreateModal()"
                        class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold px-4 py-2.5 rounded-xl shadow transition">
                        <i class="fas fa-user-plus text-sm"></i>
                        <span>Tambah User</span>
                    </button>
                </div>

                {{-- Alert Sukses --}}
                @if(session('success'))
                <div id="alertSuccess" class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 mb-5 text-sm">
                    <i class="fas fa-check-circle text-emerald-500"></i>
                    <span>{{ session('success') }}</span>
                    <button onclick="document.getElementById('alertSuccess').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600"><i class="fas fa-times"></i></button>
                </div>
                @endif

                {{-- Alert Error --}}
                @if(session('error'))
                <div id="alertError" class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                    <span>{{ session('error') }}</span>
                    <button onclick="document.getElementById('alertError').remove()" class="ml-auto text-red-400 hover:text-red-600"><i class="fas fa-times"></i></button>
                </div>
                @endif

                {{-- Statistik User --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-6">
                    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
                        <p class="text-gray-500 text-xs font-medium">Total User</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">{{ $users->count() }}</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
                        <p class="text-gray-500 text-xs font-medium">Admin</p>
                        <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $users->where('role', 'admin')->count() }}</p>
                    </div>
                    <div class="bg-white rounded-2xl shadow-sm p-4 border border-gray-100">
                        <p class="text-gray-500 text-xs font-medium">Petugas</p>
                        <p class="text-2xl font-bold text-blue-600 mt-1">{{ $users->where('role', 'petugas')->count() }}</p>
                    </div>
                </div>

                {{-- Tabel User --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 sm:p-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center gap-3">
                        <h3 class="font-semibold text-gray-800">Daftar User</h3>
                        <div class="sm:ml-auto relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <input type="text" id="searchInput" oninput="filterTable()" placeholder="Cari user..."
                                class="pl-9 pr-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 w-full sm:w-56">
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm" id="userTable">
                            <thead>
                                <tr class="bg-gray-50 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                    <th class="px-5 py-3">#</th>
                                    <th class="px-5 py-3">Nama</th>
                                    <th class="px-5 py-3">Email</th>
                                    <th class="px-5 py-3">Role</th>
                                    <th class="px-5 py-3">Dibuat</th>
                                    <th class="px-5 py-3 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100" id="tableBody">
                                @forelse($users as $i => $user)
                                <tr class="hover:bg-gray-50 transition user-row">
                                    <td class="px-5 py-4 text-gray-400 font-medium">{{ $i + 1 }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-800 user-name">{{ $user->name }}</p>
                                                @if($user->id === auth()->id())
                                                    <span class="text-xs text-emerald-500 font-medium">(Anda)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-gray-600 user-email">{{ $user->email }}</td>
                                    <td class="px-5 py-4">
                                        @if($user->role === 'admin')
                                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">Admin</span>
                                        @else
                                            <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">Petugas</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-gray-400 text-xs">{{ $user->created_at->format('d M Y') }}</td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <button onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->role }}')"
                                                class="w-8 h-8 bg-blue-50 hover:bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center transition" title="Edit">
                                                <i class="fas fa-edit text-xs"></i>
                                            </button>
                                            @if($user->id !== auth()->id())
                                            <button onclick="openDeleteModal({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                                class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg flex items-center justify-center transition" title="Hapus">
                                                <i class="fas fa-trash text-xs"></i>
                                            </button>
                                            @else
                                            <div class="w-8 h-8"></div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-5 py-12 text-center text-gray-400">
                                        <i class="fas fa-users text-4xl mb-3 block opacity-30"></i>
                                        <p class="font-medium">Belum ada user terdaftar.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </main>
    </div>

    {{-- Footer --}}
    @include('admin.partials.footer')

    {{-- ===== MODAL TAMBAH USER ===== --}}
    <div id="createModal" class="modal-overlay fixed inset-0 bg-black/50 z-[200] flex items-center justify-center p-4 hidden">
        <div class="modal-box bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-emerald-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-plus text-emerald-600"></i>
                    </div>
                    <h2 class="font-bold text-gray-800 text-lg">Tambah User Baru</h2>
                </div>
                <button onclick="closeCreateModal()" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center transition text-gray-500">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <form action="{{ route('admin.users.store') }}" method="POST" class="p-5 space-y-4">
                @csrf
                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-300 @error('name') border-red-400 @enderror">
                    @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="contoh@email.com"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-300 @error('email') border-red-400 @enderror">
                    @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Role --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                    <select name="role" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-300 bg-white @error('role') border-red-400 @enderror">
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="petugas" {{ old('role') === 'petugas' ? 'selected' : '' }}>Petugas</option>
                    </select>
                    @error('role') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password" id="createPassword" placeholder="Minimal 8 karakter"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-300 @error('password') border-red-400 @enderror">
                        <button type="button" onclick="togglePassword('createPassword', 'eyeCreate')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye text-sm" id="eyeCreate"></i>
                        </button>
                    </div>
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                {{-- Konfirmasi Password --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="createPasswordConfirm" placeholder="Ulangi password"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:border-emerald-300">
                        <button type="button" onclick="togglePassword('createPasswordConfirm', 'eyeCreateConfirm')"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <i class="fas fa-eye text-sm" id="eyeCreateConfirm"></i>
                        </button>
                    </div>
                </div>
                {{-- Footer Tombol --}}
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeCreateModal()"
                        class="flex-1 border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium py-2.5 rounded-xl text-sm transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-2.5 rounded-xl text-sm transition shadow">
                        <i class="fas fa-save mr-1.5"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== MODAL EDIT USER ===== --}}
    <div id="editModal" class="modal-overlay fixed inset-0 bg-black/50 z-[200] flex items-center justify-center p-4 hidden">
        <div class="modal-box bg-white rounded-2xl shadow-2xl w-full max-w-md">
            <div class="flex items-center justify-between p-5 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-user-edit text-blue-600"></i>
                    </div>
                    <h2 class="font-bold text-gray-800 text-lg">Edit User</h2>
                </div>
                <button onclick="closeEditModal()" class="w-8 h-8 bg-gray-100 hover:bg-gray-200 rounded-lg flex items-center justify-center transition text-gray-500">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
            <form id="editForm" action="" method="POST" class="p-5 space-y-4">
                @csrf
                @method('PUT')
                {{-- Nama --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="editName" placeholder="Masukkan nama lengkap"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300">
                </div>
                {{-- Email --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" id="editEmail" placeholder="contoh@email.com"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300">
                </div>
                {{-- Role --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Role <span class="text-red-500">*</span></label>
                    <select name="role" id="editRole" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300 bg-white">
                        <option value="admin">Admin</option>
                        <option value="petugas">Petugas</option>
                    </select>
                </div>
                {{-- Password (opsional) --}}
                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                    <p class="text-xs text-gray-500 font-medium"><i class="fas fa-info-circle mr-1"></i>Kosongkan jika tidak ingin mengubah password</p>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru</label>
                        <div class="relative">
                            <input type="password" name="password" id="editPassword" placeholder="Minimal 8 karakter"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300 bg-white">
                            <button type="button" onclick="togglePassword('editPassword', 'eyeEdit')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye text-sm" id="eyeEdit"></i>
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="editPasswordConfirm" placeholder="Ulangi password baru"
                                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 pr-10 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-300 bg-white">
                            <button type="button" onclick="togglePassword('editPasswordConfirm', 'eyeEditConfirm')"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye text-sm" id="eyeEditConfirm"></i>
                            </button>
                        </div>
                    </div>
                </div>
                {{-- Footer Tombol --}}
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeEditModal()"
                        class="flex-1 border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium py-2.5 rounded-xl text-sm transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 rounded-xl text-sm transition shadow">
                        <i class="fas fa-save mr-1.5"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== MODAL KONFIRMASI HAPUS ===== --}}
    <div id="deleteModal" class="modal-overlay fixed inset-0 bg-black/50 z-[200] flex items-center justify-center p-4 hidden">
        <div class="modal-box bg-white rounded-2xl shadow-2xl w-full max-w-sm text-center p-6">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
            </div>
            <h3 class="font-bold text-gray-800 text-lg mb-2">Konfirmasi Hapus</h3>
            <p class="text-gray-500 text-sm mb-1">Apakah Anda yakin ingin menghapus user:</p>
            <p class="font-semibold text-gray-800 text-base mb-5" id="deleteUserName"></p>
            <p class="text-xs text-red-500 mb-5">Tindakan ini tidak dapat dibatalkan!</p>
            <form id="deleteForm" action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="flex gap-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 border border-gray-200 text-gray-600 hover:bg-gray-50 font-medium py-2.5 rounded-xl text-sm transition">
                        Batal
                    </button>
                    <button type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 rounded-xl text-sm transition shadow">
                        <i class="fas fa-trash mr-1.5"></i> Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ===== SIDEBAR =====
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const overlayEl = document.getElementById('overlay');
        function closeSidebar() { sidebar.classList.remove('open'); overlayEl.classList.remove('active'); }
        function openSidebar() { sidebar.classList.add('open'); overlayEl.classList.add('active'); }
        toggleBtn.addEventListener('click', (e) => { e.stopPropagation(); sidebar.classList.contains('open') ? closeSidebar() : openSidebar(); });
        overlayEl.addEventListener('click', closeSidebar);
        window.addEventListener('resize', () => { if (window.innerWidth > 768) closeSidebar(); });

        // ===== TOGGLE PASSWORD =====
        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('fa-eye', 'fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('fa-eye-slash', 'fa-eye');
            }
        }

        // ===== MODAL CREATE =====
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // ===== MODAL EDIT =====
        function openEditModal(id, name, email, role) {
            document.getElementById('editName').value = name;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = role;
            document.getElementById('editPassword').value = '';
            document.getElementById('editPasswordConfirm').value = '';
            document.getElementById('editForm').action = '/admin/users/' + id;
            document.getElementById('editModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // ===== MODAL DELETE =====
        function openDeleteModal(id, name) {
            document.getElementById('deleteUserName').textContent = name;
            document.getElementById('deleteForm').action = '/admin/users/' + id;
            document.getElementById('deleteModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // ===== CLOSE MODAL ON BACKDROP CLICK =====
        ['createModal', 'editModal', 'deleteModal'].forEach(id => {
            document.getElementById(id).addEventListener('click', function(e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            });
        });

        // ===== SEARCH/FILTER =====
        function filterTable() {
            const q = document.getElementById('searchInput').value.toLowerCase();
            document.querySelectorAll('.user-row').forEach(row => {
                const name = row.querySelector('.user-name')?.textContent.toLowerCase() || '';
                const email = row.querySelector('.user-email')?.textContent.toLowerCase() || '';
                row.style.display = (name.includes(q) || email.includes(q)) ? '' : 'none';
            });
        }

        // ===== AUTO OPEN CREATE MODAL IF VALIDATION ERROR =====
        @if($errors->any())
            openCreateModal();
        @endif
    </script>
</body>
</html>