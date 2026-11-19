<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Perpustakaan Keliling') - Dispusip Lampung Timur</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- FontAwesome 6 Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
    
    <style>
        [x-cloak] { display: none !important; }
        @media print {
            .no-print { display: none !important; }
            .print-only { display: block !important; }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased" x-data="{ sidebarOpen: false }">

    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Sidebar Navigation -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed md:static inset-y-0 left-0 z-40 w-64 bg-slate-900 text-slate-300 transition-transform duration-300 ease-in-out md:translate-x-0 flex flex-col shadow-2xl flex-shrink-0">
            <!-- App Brand / Header -->
            <div class="h-20 flex items-center px-6 border-b border-slate-800 bg-slate-950/60">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-sky-400 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 mr-3">
                    <i class="fa-solid fa-book-open-reader text-lg"></i>
                </div>
                <div>
                    <h1 class="text-sm font-bold text-white tracking-wide leading-tight">PERPUS KELILING</h1>
                    <p class="text-[11px] text-slate-400 font-medium">Dispusip Lampung Timur</p>
                </div>
            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 px-4 py-5 space-y-1.5 overflow-y-auto">
                <div class="px-3 pb-2 text-[10px] font-bold tracking-wider text-slate-500 uppercase">Menu Utama</div>
                
                <a href="{{ route('dashboard') }}" class="flex items-center px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-chart-pie w-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Dashboard</span>
                </a>

                <div class="pt-4 px-3 pb-2 text-[10px] font-bold tracking-wider text-slate-500 uppercase">Master Data</div>

                <a href="{{ route('sekolah.index') }}" class="flex items-center px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('sekolah.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-school w-5 mr-3 {{ request()->routeIs('sekolah.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Data Sekolah</span>
                </a>

                @if(Auth::user()?->isAdmin())
                <a href="{{ route('pustakawan.index') }}" class="flex items-center px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('pustakawan.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-user-tie w-5 mr-3 {{ request()->routeIs('pustakawan.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Data Pustakawan</span>
                </a>
                @endif

                <a href="{{ route('anggota.index') }}" class="flex items-center px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('anggota.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-id-card w-5 mr-3 {{ request()->routeIs('anggota.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Data Anggota Siswa</span>
                </a>

                <div class="pt-4 px-3 pb-2 text-[10px] font-bold tracking-wider text-slate-500 uppercase">Aktivitas Layanan</div>

                <a href="{{ route('berita-acara.index') }}" class="flex items-center px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('berita-acara.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-file-signature w-5 mr-3 {{ request()->routeIs('berita-acara.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Berita Acara & Presensi</span>
                </a>

                <div class="pt-4 px-3 pb-2 text-[10px] font-bold tracking-wider text-slate-500 uppercase">Pelaporan</div>

                <a href="{{ route('laporan.anggota') }}" class="flex items-center px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('laporan.anggota*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-users-rectangle w-5 mr-3 {{ request()->routeIs('laporan.anggota*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Laporan Anggota</span>
                </a>

                <a href="{{ route('laporan.kunjungan') }}" class="flex items-center px-3.5 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('laporan.kunjungan*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-400 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-van-shuttle w-5 mr-3 {{ request()->routeIs('laporan.kunjungan*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Laporan Kunjungan</span>
                </a>
            </nav>

            <!-- User Info & Logout -->
            <div class="p-4 border-t border-slate-800 bg-slate-950/40">
                <div class="flex items-center justify-between">
                    <a href="{{ route('profile.show') }}" class="flex items-center min-w-0 mr-2 group hover:opacity-90 transition">
                        <div class="w-9 h-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-indigo-400 font-bold text-sm mr-2.5 flex-shrink-0 group-hover:border-indigo-500">
                            {{ strtoupper(substr(Auth::user()?->username ?? 'A', 0, 1)) }}
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-bold text-white truncate group-hover:text-indigo-300">{{ Auth::user()?->nama_display ?? 'Petugas' }}</p>
                            <div class="flex items-center gap-1.5 mt-0.5">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold {{ Auth::user()?->isAdmin() ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' }}">
                                    {{ Auth::user()?->isAdmin() ? 'Administrator' : 'Petugas' }}
                                </span>
                                <span class="text-[10px] text-slate-400">&bull; <span class="text-indigo-400">Profil</span></span>
                            </div>
                        </div>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" title="Keluar / Logout" class="text-slate-400 hover:text-rose-400 p-2 rounded-lg hover:bg-slate-800 transition">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Overlay backdrop for mobile -->
        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 z-30 bg-black/50 backdrop-blur-sm md:hidden"></div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Topbar Header -->
            <header class="h-20 bg-white border-b border-slate-200 px-4 md:px-8 flex items-center justify-between no-print sticky top-0 z-20">
                <div class="flex items-center">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-slate-500 hover:text-slate-700 p-2 mr-2 rounded-lg">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                    <div>
                        <h2 class="text-lg md:text-xl font-bold text-slate-900 leading-snug">@yield('page_title', 'Dashboard')</h2>
                        <p class="text-xs text-slate-500 hidden sm:block">@yield('page_subtitle', 'Sistem Pengolahan Data Perpustakaan Keliling')</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="hidden lg:flex items-center text-xs font-semibold px-3 py-1.5 rounded-full border {{ Auth::user()?->isAdmin() ? 'bg-indigo-50 text-indigo-700 border-indigo-200' : 'bg-emerald-50 text-emerald-700 border-emerald-200' }}">
                        <i class="fa-solid {{ Auth::user()?->isAdmin() ? 'fa-shield-halved' : 'fa-clipboard-user' }} mr-1.5"></i>
                        <span>{{ Auth::user()?->isAdmin() ? 'Administrator (' . (Auth::user()?->nama_display ?? 'Zahra') . ')' : 'Petugas (' . (Auth::user()?->nama_display ?? 'Dedi Irawan, S.Kom., M.T.I') . ')' }}</span>
                    </div>

                    <div class="hidden sm:flex items-center text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-full border border-slate-200">
                        <i class="fa-regular fa-calendar mr-2 text-indigo-600"></i>
                        <span>{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                </div>
            </header>

            <!-- Main Body -->
            <main class="flex-1 p-4 md:p-8 overflow-y-auto">
                <!-- Flash Messages -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center justify-between text-sm shadow-sm transition">
                        <div class="flex items-center">
                            <i class="fa-solid fa-circle-check text-emerald-500 text-lg mr-3"></i>
                            <span class="font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-emerald-500 hover:text-emerald-700">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 flex items-center justify-between text-sm shadow-sm transition">
                        <div class="flex items-center">
                            <i class="fa-solid fa-circle-exclamation text-rose-500 text-lg mr-3"></i>
                            <span class="font-medium">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-rose-500 hover:text-rose-700">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @if(session('info'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 rounded-xl bg-sky-50 border border-sky-200 text-sky-800 flex items-center justify-between text-sm shadow-sm transition">
                        <div class="flex items-center">
                            <i class="fa-solid fa-circle-info text-sky-500 text-lg mr-3"></i>
                            <span class="font-medium">{{ session('info') }}</span>
                        </div>
                        <button @click="show = false" class="text-sky-500 hover:text-sky-700">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-slate-200 py-4 px-6 md:px-8 text-center md:text-left text-xs text-slate-500 flex flex-col md:flex-row justify-between items-center gap-2 no-print">
                <p>&copy; {{ date('Y') }} Dinas Perpustakaan dan Kearsipan Kabupaten Lampung Timur. All rights reserved.</p>
                <p class="font-medium text-slate-400">Tugas Akhir: Putri Damayanti (16410031) - UM Metro</p>
            </footer>
        </div>
    </div>

    <script>
    window.initAjaxTable = function(options) {
        const form = document.querySelector(options.formSelector);
        const container = document.querySelector(options.containerSelector);
        if (!form || !container) return;

        let debounceTimer;
        const searchIcon = form.querySelector('.search-icon');
        const originalIconHtml = searchIcon ? searchIcon.innerHTML : '';

        function showLoading() {
            container.classList.add('opacity-40', 'pointer-events-none');
            if (searchIcon) {
                searchIcon.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-xs text-indigo-600"></i>';
            }
        }

        function hideLoading() {
            container.classList.remove('opacity-40', 'pointer-events-none');
            if (searchIcon) {
                searchIcon.innerHTML = originalIconHtml;
            }
        }

        function buildUrl() {
            const formData = new FormData(form);
            const params = new URLSearchParams();
            for (const [k, v] of formData.entries()) {
                if (v !== '' && v !== null && v !== undefined) {
                    params.append(k, v);
                }
            }
            const action = form.getAttribute('action') || window.location.pathname;
            const query = params.toString();
            return query ? `${action}?${query}` : action;
        }

        function loadData(url) {
            showLoading();
            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error('Gagal memuat data');
                return response.text();
            })
            .then(html => {
                container.innerHTML = html;
                window.history.pushState(null, '', url);
                bindPagination();
                if (typeof options.onUpdate === 'function') {
                    options.onUpdate(url, form);
                }
            })
            .catch(err => {
                console.error('AJAX load error:', err);
            })
            .finally(() => {
                hideLoading();
            });
        }

        function triggerSearch(immediate = false) {
            clearTimeout(debounceTimer);
            if (immediate) {
                loadData(buildUrl());
            } else {
                debounceTimer = setTimeout(() => {
                    loadData(buildUrl());
                }, 300);
            }
        }

        // Live typing pada input text / search
        form.querySelectorAll('input[type="text"], input[type="search"]').forEach(input => {
            input.addEventListener('input', () => triggerSearch(false));
        });

        // Perubahan pada select filter dan input tanggal
        form.querySelectorAll('select, input[type="date"]').forEach(el => {
            el.addEventListener('change', () => triggerSearch(true));
        });

        // Mencegah reload penuh saat form di-submit / ditekan Enter
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            triggerSearch(true);
        });

        // Tombol reset pencarian
        const resetBtn = form.querySelector('.btn-reset');
        if (resetBtn) {
            resetBtn.addEventListener('click', (e) => {
                e.preventDefault();
                form.querySelectorAll('input').forEach(i => i.value = '');
                form.querySelectorAll('select').forEach(s => s.value = '');
                const cleanUrl = form.getAttribute('action') || window.location.pathname;
                loadData(cleanUrl);
            });
        }

        // Intercept klik pagination pada tabel hasil AJAX
        function bindPagination() {
            container.querySelectorAll('a[href*="page="]').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    loadData(link.getAttribute('href'));
                });
            });
        }

        bindPagination();
    };
    </script>

    @stack('scripts')
</body>
</html>

