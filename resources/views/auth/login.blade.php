<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Perpustakaan Keliling Lampung Timur</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 min-h-screen flex items-center justify-center p-4 font-['Plus_Jakarta_Sans',sans-serif]">

    <div class="w-full max-w-md">
        <!-- Logo & Header -->
        <div class="text-center mb-8">
            <div class="inline-flex w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-500 to-sky-400 items-center justify-center text-white shadow-xl shadow-indigo-500/25 mb-4 ring-8 ring-indigo-500/10">
                <i class="fa-solid fa-book-open-reader text-2xl"></i>
            </div>
            <h1 class="text-2xl font-extrabold text-white tracking-tight">Sistem Perpustakaan Keliling</h1>
            <p class="text-sm text-slate-400 mt-1">Dinas Perpustakaan dan Kearsipan Lampung Timur</p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900/90 backdrop-blur-xl border border-slate-800 rounded-3xl p-7 md:p-9 shadow-2xl shadow-black/50">
            <h2 class="text-lg font-bold text-white mb-2">Masuk ke Sistem</h2>
            <p class="text-xs text-slate-400 mb-6">Silakan masukkan username dan password petugas Anda.</p>

            @if(session('info'))
                <div class="mb-5 p-3.5 rounded-xl bg-sky-500/10 border border-sky-500/20 text-sky-300 text-xs flex items-center">
                    <i class="fa-solid fa-circle-info mr-2.5 text-base"></i>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if(isset($errors) && $errors->any())
                <div class="mb-5 p-3.5 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-300 text-xs">
                    <div class="flex items-center mb-1 font-semibold">
                        <i class="fa-solid fa-circle-exclamation mr-2"></i>
                        <span>Autentikasi Gagal</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-rose-300/90 pl-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="username" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <input type="text" name="username" id="username" value="{{ old('username', 'admin') }}" required autofocus
                            class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-950/80 border border-slate-700/80 text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" name="password" id="password" value="admin123" required
                            class="w-full pl-10 pr-4 py-3 rounded-xl bg-slate-950/80 border border-slate-700/80 text-white placeholder-slate-500 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-700 bg-slate-950 text-indigo-600 focus:ring-indigo-500">
                        <span class="ml-2 text-xs text-slate-400">Ingat Saya</span>
                    </label>
                    <span class="text-[11px] text-slate-500">D-III SI UM Metro</span>
                </div>

                <button type="submit" 
                    class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-500 hover:to-indigo-600 text-white text-sm font-bold shadow-lg shadow-indigo-600/30 transition transform hover:-translate-y-0.5 active:translate-y-0">
                    Masuk ke Sistem <i class="fa-solid fa-arrow-right ml-2 text-xs"></i>
                </button>
            </form>

            <!-- Demo helper card -->
            <div class="mt-6 pt-5 border-t border-slate-800/80">
                <p class="text-[11px] font-semibold text-slate-400 text-center uppercase tracking-wider mb-2.5">
                    <i class="fa-solid fa-users mr-1 text-indigo-400"></i> Pilihan Akun Login (Klik untuk Isi Cepat)
                </p>
                <div class="grid grid-cols-2 gap-2">
                    <button type="button" onclick="fillAccount('admin', 'admin123')"
                        class="p-2.5 rounded-xl bg-slate-950/70 hover:bg-indigo-950/80 border border-slate-800 hover:border-indigo-600/50 text-left transition group">
                        <div class="flex items-center text-xs font-bold text-white group-hover:text-indigo-300">
                            <i class="fa-solid fa-shield-halved text-indigo-400 mr-1.5 text-[11px]"></i> Admin
                        </div>
                        <p class="text-[11px] text-slate-400 truncate mt-0.5">Zahra Nurhaliza</p>
                        <p class="text-[10px] font-mono text-indigo-400/80 mt-1">admin &bull; admin123</p>
                    </button>

                    <button type="button" onclick="fillAccount('dedi', 'petugas123')"
                        class="p-2.5 rounded-xl bg-slate-950/70 hover:bg-emerald-950/80 border border-slate-800 hover:border-emerald-600/50 text-left transition group">
                        <div class="flex items-center text-xs font-bold text-white group-hover:text-emerald-300">
                            <i class="fa-solid fa-clipboard-user text-emerald-400 mr-1.5 text-[11px]"></i> Petugas
                        </div>
                        <p class="text-[11px] text-slate-400 truncate mt-0.5" title="Dedi Irawan, S.Kom., M.T.I">Dedi Irawan, S.Kom., M.T.I</p>
                        <p class="text-[10px] font-mono text-emerald-400/80 mt-1">dedi &bull; petugas123</p>
                    </button>
                </div>
            </div>
        </div>

        <script>
            function fillAccount(username, password) {
                document.getElementById('username').value = username;
                document.getElementById('password').value = password;
            }
        </script>

        <p class="text-center text-xs text-slate-600 mt-6">
            &copy; 2019 - {{ date('Y') }} Dinas Perpustakaan dan Kearsipan Lampung Timur
        </p>
    </div>

</body>
</html>

