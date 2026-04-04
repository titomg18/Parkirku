{{-- resources/views/admin/profile.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil & Pengaturan - ParkirKu</title>
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
        .tab-content { display: none; }
        .tab-content.active { display: block; }
        .tab-nav-btn.active { background: #059669; color: white; box-shadow: 0 4px 12px rgba(5,150,105,0.3); }
    </style>
</head>
<body>
@include('admin.partials.navbar')
<div id="overlay" class="overlay fixed inset-0 bg-black/50 z-50 lg:hidden"></div>
<div class="flex pt-16 min-h-screen">
    @include('admin.partials.sidebar')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
        <div class="fade-up max-w-3xl mx-auto">

            {{-- Header --}}
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Profil & Pengaturan</h1>
                <p class="text-gray-500 text-sm mt-0.5">Kelola informasi akun dan keamanan Anda</p>
            </div>

            {{-- Alert --}}
            @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-300 text-emerald-700 rounded-xl px-4 py-3 mb-5 flex items-center gap-3 text-sm">
                <i class="fas fa-check-circle text-emerald-500"></i>
                {{ session('success') }}
            </div>
            @endif
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 mb-5 text-sm">
                <p class="font-semibold mb-1"><i class="fas fa-exclamation-circle mr-1"></i>Terjadi kesalahan:</p>
                @foreach($errors->all() as $e)
                <p class="text-xs mt-0.5">• {{ $e }}</p>
                @endforeach
            </div>
            @endif

            {{-- Avatar & Info Card --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-5">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-5">
                    {{-- Avatar --}}
                    <div class="relative">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-600 flex items-center justify-center text-white font-bold text-3xl shadow-lg">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-white text-xs"></i>
                        </div>
                    </div>
                    {{-- Info --}}
                    <div class="flex-1 text-center sm:text-left">
                        <h2 class="text-xl font-bold text-gray-800">{{ auth()->user()->name }}</h2>
                        <p class="text-gray-500 text-sm">{{ auth()->user()->email }}</p>
                        <div class="flex items-center justify-center sm:justify-start gap-2 mt-2 flex-wrap">
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold capitalize">
                                <i class="fas fa-shield-alt mr-1"></i>{{ auth()->user()->role }}
                            </span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">
                                <i class="fas fa-calendar mr-1"></i>Bergabung {{ auth()->user()->created_at->isoFormat('MMMM Y') }}
                            </span>
                        </div>
                    </div>
                    {{-- Stats --}}
                    <div class="flex gap-4 text-center">
                        <div class="px-4 py-3 bg-gray-50 rounded-xl">
                            <p class="text-xs text-gray-500">Login</p>
                            <p class="font-bold text-gray-800">Admin</p>
                        </div>
                        <div class="px-4 py-3 bg-emerald-50 rounded-xl">
                            <p class="text-xs text-gray-500">Status</p>
                            <p class="font-bold text-emerald-600 text-sm">Aktif</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="flex gap-2 mb-5">
                <button onclick="switchTab('profil')" id="tab-profil"
                    class="tab-nav-btn active flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold transition">
                    <i class="fas fa-user"></i> Edit Profil
                </button>
                <button onclick="switchTab('password')" id="tab-password"
                    class="tab-nav-btn flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition">
                    <i class="fas fa-lock"></i> Ganti Password
                </button>
                <button onclick="switchTab('info')" id="tab-info"
                    class="tab-nav-btn flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 transition">
                    <i class="fas fa-info-circle"></i> Info Sistem
                </button>
            </div>

            {{-- Tab: Edit Profil --}}
            <div id="content-profil" class="tab-content active bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-5 flex items-center gap-2">
                    <i class="fas fa-user-edit text-emerald-500"></i> Informasi Akun
                </h3>
                <form action="{{ route('admin.profile.update') }}" method="POST" class="space-y-5">
                    @csrf @method('PUT')
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 transition">
                        @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                            class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 transition">
                        @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 mb-1">Role</p>
                            <p class="font-semibold text-gray-700 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-4">
                            <p class="text-xs text-gray-500 mb-1">Dibuat</p>
                            <p class="font-semibold text-gray-700">{{ auth()->user()->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-xl transition shadow flex items-center justify-center gap-2">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </form>
            </div>

            {{-- Tab: Ganti Password --}}
            <div id="content-password" class="tab-content bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-2 flex items-center gap-2">
                    <i class="fas fa-lock text-blue-500"></i> Ubah Password
                </h3>
                <p class="text-sm text-gray-500 mb-5">Gunakan password yang kuat minimal 8 karakter</p>
                <form action="{{ route('admin.profile.password') }}" method="POST" class="space-y-5">
                    @csrf @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password Saat Ini</label>
                        <div class="relative">
                            <input type="password" name="current_password" id="cur_pw"
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition">
                            <button type="button" onclick="togglePw('cur_pw','eye_cur')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i id="eye_cur" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password Baru</label>
                        <div class="relative">
                            <input type="password" name="password" id="new_pw"
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition"
                                oninput="checkStrength(this.value)">
                            <button type="button" onclick="togglePw('new_pw','eye_new')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i id="eye_new" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                        {{-- Strength bar --}}
                        <div class="mt-2">
                            <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                <div id="strengthBar" class="h-full rounded-full transition-all duration-300" style="width:0%"></div>
                            </div>
                            <p id="strengthLabel" class="text-xs text-gray-400 mt-1">Masukkan password baru</p>
                        </div>
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="conf_pw"
                                class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 pr-10 text-sm focus:outline-none focus:border-blue-400 focus:ring-2 focus:ring-blue-100 transition">
                            <button type="button" onclick="togglePw('conf_pw','eye_conf')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <i id="eye_conf" class="fas fa-eye text-sm"></i>
                            </button>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-3 text-xs text-blue-600 space-y-1">
                        <p class="font-semibold">Tips password aman:</p>
                        <p>• Minimal 8 karakter</p>
                        <p>• Kombinasi huruf besar, kecil, angka & simbol</p>
                        <p>• Jangan gunakan tanggal lahir atau nama</p>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow flex items-center justify-center gap-2">
                        <i class="fas fa-key"></i> Ubah Password
                    </button>
                </form>
            </div>

            {{-- Tab: Info Sistem --}}
            <div id="content-info" class="tab-content bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-5 flex items-center gap-2">
                    <i class="fas fa-server text-purple-500"></i> Informasi Sistem
                </h3>
                <div class="space-y-3">
                    @php
                    $infos = [
                        ['label'=>'Nama Aplikasi',     'value'=>'ParkirKu',                  'icon'=>'fa-parking',       'color'=>'emerald'],
                        ['label'=>'Versi',             'value'=>'1.0.0',                     'icon'=>'fa-code-branch',   'color'=>'blue'],
                        ['label'=>'Framework',         'value'=>'Laravel '.app()->version(), 'icon'=>'fa-layer-group',   'color'=>'red'],
                        ['label'=>'PHP Version',       'value'=>phpversion(),                'icon'=>'fa-code',          'color'=>'indigo'],
                        ['label'=>'Database',          'value'=>config('database.default'),  'icon'=>'fa-database',      'color'=>'orange'],
                        ['label'=>'Timezone',          'value'=>config('app.timezone'),      'icon'=>'fa-globe',         'color'=>'teal'],
                        ['label'=>'Environment',       'value'=>app()->environment(),        'icon'=>'fa-cog',           'color'=>'gray'],
                        ['label'=>'Server Time',       'value'=>now()->format('d/m/Y H:i:s').' WIB', 'icon'=>'fa-clock','color'=>'purple'],
                    ];
                    @endphp
                    @foreach($infos as $info)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-{{ $info['color'] }}-100 rounded-lg flex items-center justify-center">
                                <i class="fas {{ $info['icon'] }} text-{{ $info['color'] }}-600 text-xs"></i>
                            </div>
                            <span class="text-sm text-gray-600">{{ $info['label'] }}</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-800 font-mono">{{ $info['value'] }}</span>
                    </div>
                    @endforeach
                </div>

                <div class="mt-5 pt-5 border-t border-gray-100">
                    <h4 class="font-semibold text-gray-700 mb-3 text-sm">Tentang ParkirKu</h4>
                    <p class="text-sm text-gray-500">Sistem Manajemen Parkir modern berbasis web. Dilengkapi fitur karcis barcode, perhitungan tarif otomatis, tarif inap, laporan harian, dan manajemen petugas.</p>
                    <p class="text-xs text-gray-400 mt-2">© {{ date('Y') }} ParkirKu. All rights reserved.</p>
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

    function switchTab(name) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-nav-btn').forEach(btn => {
            btn.classList.remove('active','bg-emerald-600','text-white');
            btn.classList.add('bg-white','text-gray-600','border','border-gray-200');
        });
        document.getElementById('content-'+name).classList.add('active');
        const btn = document.getElementById('tab-'+name);
        btn.classList.add('active');
        btn.classList.remove('bg-white','text-gray-600','border','border-gray-200');
    }

    function togglePw(id, eyeId) {
        const input = document.getElementById(id);
        const icon  = document.getElementById(eyeId);
        if(input.type === 'password') { input.type='text'; icon.className='fas fa-eye-slash text-sm'; }
        else { input.type='password'; icon.className='fas fa-eye text-sm'; }
    }

    function checkStrength(pw) {
        let score = 0;
        if(pw.length >= 8)  score++;
        if(/[A-Z]/.test(pw)) score++;
        if(/[0-9]/.test(pw)) score++;
        if(/[^A-Za-z0-9]/.test(pw)) score++;
        const bar   = document.getElementById('strengthBar');
        const label = document.getElementById('strengthLabel');
        const levels = [
            {pct:'0%',  cls:'bg-gray-300', txt:'Masukkan password baru'},
            {pct:'25%', cls:'bg-red-500',   txt:'Terlalu lemah'},
            {pct:'50%', cls:'bg-orange-500', txt:'Lemah'},
            {pct:'75%', cls:'bg-yellow-500', txt:'Sedang'},
            {pct:'100%',cls:'bg-emerald-500',txt:'Kuat 💪'},
        ];
        const l = levels[score];
        bar.style.width = l.pct;
        bar.className = 'h-full rounded-full transition-all duration-300 ' + l.cls;
        label.textContent = l.txt;
    }

    // Auto switch ke tab password jika ada error password
    @if($errors->hasAny(['current_password','password']))
    switchTab('password');
    @endif
</script>
</body>
</html>