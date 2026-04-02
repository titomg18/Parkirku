{{-- resources/views/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ParkirKu | Parkir Mudah & Aman</title>
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Font Awesome 6 --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    {{-- Google Fonts: Plus Jakarta Sans --}}
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e9edf2 100%);
        }
        /* Efek pattern halus di background */
        .bg-pattern {
            background-image: radial-gradient(rgba(16, 185, 129, 0.05) 1px, transparent 1px);
            background-size: 20px 20px;
        }
        /* Animasi masuk */
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-slide-up {
            animation: slideUp 0.5s ease-out forwards;
        }
    </style>
</head>
<body class="bg-pattern min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-lg animate-slide-up">
        <div class="bg-white rounded-3xl shadow-2xl shadow-gray-200 overflow-hidden">
            {{-- Header dengan ilustrasi --}}
            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 p-6 text-center">
                <div class="inline-flex items-center justify-center bg-white/20 rounded-full p-3 mb-3">
                    <i class="fas fa-parking text-3xl text-white"></i>
                </div>
                <h1 class="text-2xl font-bold text-white">ParkirKu</h1>
                <p class="text-emerald-100 text-sm mt-1">Sistem Parkir Digital</p>
            </div>

            {{-- Body form --}}
            <div class="p-8">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-800">Selamat Datang Kembali</h2>
                    <p class="text-gray-500 text-sm mt-1">Masuk untuk mengelola parkir Anda</p>
                </div>

                {{-- Error Messages --}}
                @if ($errors->any())
                    <div class="mb-5 bg-red-50 border border-red-200 text-red-600 p-3 rounded-xl flex items-start space-x-2">
                        <i class="fas fa-circle-exclamation mt-0.5"></i>
                        <div class="flex-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Success Message --}}
                @if (session('success'))
                    <div class="mb-5 bg-green-50 border border-green-200 text-green-600 p-3 rounded-xl flex items-center space-x-2">
                        <i class="fas fa-check-circle"></i>
                        <span class="text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ url('/login') }}" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-gray-700 text-sm font-medium mb-1">
                            <i class="fas fa-envelope mr-1 text-emerald-500"></i> Alamat Email
                        </label>
                        <input type="email" name="email" id="email" required autofocus
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 transition"
                               placeholder="nama@perusahaan.com" value="{{ old('email') }}">
                    </div>

                    <div>
                        <label for="password" class="block text-gray-700 text-sm font-medium mb-1">
                            <i class="fas fa-key mr-1 text-emerald-500"></i> Kata Sandi
                        </label>
                        <input type="password" name="password" id="password" required
                               class="w-full border border-gray-300 rounded-xl px-4 py-3 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 transition"
                               placeholder="Masukkan password">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="remember" class="h-4 w-4 text-emerald-600 focus:ring-emerald-500 border-gray-300 rounded">
                            <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                        </label>
                        <a href="#" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">
                            Lupa password?
                        </a>
                    </div>

                    <button type="submit"
                            class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white font-semibold py-3 rounded-xl transition transform hover:scale-[1.02] shadow-md flex items-center justify-center space-x-2">
                        <i class="fas fa-arrow-right-to-bracket"></i>
                        <span>Masuk</span>
                    </button>
                </form>

                {{-- Bagian registrasi dihapus --}}

            </div>

            {{-- Footer info --}}
            <div class="bg-gray-50 px-8 py-4 text-center text-xs text-gray-400 border-t border-gray-100">
                <i class="fas fa-shield-alt mr-1"></i> Keamanan data terjamin
                <span class="mx-2">•</span>
                <i class="fas fa-headset mr-1"></i> Support 24/7
            </div>
        </div>

        {{-- Tagline --}}
        <p class="text-center text-gray-400 text-xs mt-6">
            &copy; {{ date('Y') }} ParkirKu — Solusi Parkir Modern
        </p>
    </div>

</body>
</html>