<nav class="sticky top-0 z-50 bg-gradient-to-r from-green-800 to-green-700 text-white shadow-lg">
    <!-- Background pattern dengan efek lebih halus -->
    <div class="absolute inset-0 opacity-5 bg-[url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" width=\"40\" height=\"40\" viewBox=\"0 0 40 40\"><path d=\"M20,5 L35,12 L35,28 L20,35 L5,28 L5,12 Z\" fill=\"white\" stroke=\"%230d5c3e\" stroke-width=\"0.5\"/></svg>')]"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex justify-between items-center h-16">
            <!-- Logo/Brand dengan efek hover yang lebih baik -->
            <div class="flex-shrink-0 flex items-center">
                <div class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 flex items-center justify-center bg-white rounded-full shadow-lg ring-2 ring-green-400 ring-offset-2 ring-offset-green-800 transition-transform duration-300 group-hover:scale-105">
                        <img 
                            src="{{ asset('storage/logo/logopn.png') }}" 
                            alt="Logo PN Gorontalo"
                            class="w-8 h-8 object-contain"
                        >
                    </div>
                    <a href="{{ route('dashboard') }}" 
                       class="text-xl font-bold tracking-tight hover:text-green-100 transition-colors duration-300 group-hover:scale-[1.02] transform">
                        <span class="text-green-100">E-SIDAK</span>
                        <span class="font-normal text-green-200 text-sm ml-1">PN Gorontalo</span>
                    </a>
                </div>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:block">
                <div class="ml-8 flex items-center space-x-0.5">
                    @auth
                        <!-- Menu utama -->
                        <div class="flex items-center space-x-0.5">
                            <!-- Dashboard -->
                            <a href="{{ route('dashboard') }}" 
                               class="relative px-4 py-2.5 rounded-lg transition-all duration-200 hover:bg-green-600/80 hover:shadow-md group {{ request()->routeIs('dashboard') ? 'bg-green-600 shadow-md' : '' }}">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-tachometer-alt text-sm {{ request()->routeIs('dashboard') ? 'text-green-100' : 'text-green-300' }}"></i>
                                    <span class="text-sm font-medium">Dashboard</span>
                                </div>
                                @if(request()->routeIs('dashboard'))
                                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3/4 h-0.5 bg-green-300 rounded-t-lg"></div>
                                @endif
                            </a>

                            <!-- Cetak Laporan -->
                            <a href="{{ route('laporan') }}" 
                               class="relative px-4 py-2.5 rounded-lg transition-all duration-200 hover:bg-green-600/80 hover:shadow-md group {{ request()->routeIs('laporan*') ? 'bg-green-600 shadow-md' : '' }}">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-print text-sm {{ request()->routeIs('laporan*') ? 'text-green-100' : 'text-green-300' }}"></i>
                                    <span class="text-sm font-medium">Cetak Laporan</span>
                                </div>
                                @if(request()->routeIs('laporan*'))
                                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3/4 h-0.5 bg-green-300 rounded-t-lg"></div>
                                @endif
                            </a>

                            <!-- Evaluasi Kerja -->
                            <a href="{{ route('evaluasi-kerja.index') }}" 
                               class="relative px-4 py-2.5 rounded-lg transition-all duration-200 hover:bg-green-600/80 hover:shadow-md group {{ request()->routeIs('evaluasi-kerja*') ? 'bg-green-600 shadow-md' : '' }}">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-file-word text-sm {{ request()->routeIs('evaluasi-kerja*') ? 'text-green-100' : 'text-green-300' }}"></i>
                                    <span class="text-sm font-medium">Evaluasi Kerja</span>
                                </div>
                                @if(request()->routeIs('evaluasi-kerja*'))
                                <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3/4 h-0.5 bg-green-300 rounded-t-lg"></div>
                                @endif
                            </a>

                            @if(auth()->user()->isSuperAdmin())
                                <!-- Dropdowns untuk Super Admin -->
                                <div class="flex items-center space-x-0.5">
                                    <!-- Kepaniteraan Dropdown -->
                                    <div class="relative group">
                                        <button class="flex items-center space-x-2 px-4 py-2.5 rounded-lg transition-all duration-200 hover:bg-green-600/80 hover:shadow-md {{ request()->routeIs(['perdata','pidana','tipikor','phi','hukum']) ? 'bg-green-600 shadow-md' : '' }}">
                                            <i class="fas fa-gavel text-sm text-green-300"></i>
                                            <span class="text-sm font-medium">Kepaniteraan</span>
                                            <svg class="w-3 h-3 ml-1 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        
                                        <!-- Dropdown Content -->
                                        <div class="absolute left-0 mt-1 w-56 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform -translate-y-2 group-hover:translate-y-0 z-50">
                                            <div class="py-2 bg-white rounded-lg shadow-xl ring-1 ring-black ring-opacity-5">
                                                <div class="px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-500 rounded-t-lg">
                                                    <div class="flex items-center space-x-2">
                                                        <i class="fas fa-gavel text-sm"></i>
                                                        <span class="text-sm font-semibold">Jenis Perkara</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="space-y-0.5 p-2">
                                                    <a href="{{ route('perdata') }}" class="flex items-center space-x-3 px-3 py-2.5 text-sm text-gray-700 rounded-md hover:bg-green-50 transition-colors duration-150 {{ request()->routeIs('perdata') ? 'bg-green-50 text-green-700 font-medium' : '' }}">
                                                        <i class="fas fa-balance-scale text-green-600 w-5"></i>
                                                        <span>Perdata</span>
                                                    </a>
                                                    
                                                    <a href="{{ route('pidana') }}" class="flex items-center space-x-3 px-3 py-2.5 text-sm text-gray-700 rounded-md hover:bg-green-50 transition-colors duration-150 {{ request()->routeIs('pidana') ? 'bg-green-50 text-green-700 font-medium' : '' }}">
                                                        <i class="fas fa-gavel text-green-600 w-5"></i>
                                                        <span>Pidana</span>
                                                    </a>
                                                    
                                                    <a href="{{ route('tipikor') }}" class="flex items-center space-x-3 px-3 py-2.5 text-sm text-gray-700 rounded-md hover:bg-green-50 transition-colors duration-150 {{ request()->routeIs('tipikor') ? 'bg-green-50 text-green-700 font-medium' : '' }}">
                                                        <i class="fas fa-landmark text-green-600 w-5"></i>
                                                        <span>Tipikor</span>
                                                    </a>
                                                    
                                                    <a href="{{ route('phi') }}" class="flex items-center space-x-3 px-3 py-2.5 text-sm text-gray-700 rounded-md hover:bg-green-50 transition-colors duration-150 {{ request()->routeIs('phi') ? 'bg-green-50 text-green-700 font-medium' : '' }}">
                                                        <i class="fas fa-copyright text-green-600 w-5"></i>
                                                        <span>PHI</span>
                                                    </a>
                                                    
                                                    <a href="{{ route('hukum') }}" class="flex items-center space-x-3 px-3 py-2.5 text-sm text-gray-700 rounded-md hover:bg-green-50 transition-colors duration-150 {{ request()->routeIs('hukum') ? 'bg-green-50 text-green-700 font-medium' : '' }}">
                                                        <i class="fas fa-book text-green-600 w-5"></i>
                                                        <span>Hukum</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kesekretariatan Dropdown -->
                                    <div class="relative group">
                                        <button class="flex items-center space-x-2 px-4 py-2.5 rounded-lg transition-all duration-200 hover:bg-green-600/80 hover:shadow-md {{ request()->routeIs(['ptip','umum-keuangan','kepegawaian']) ? 'bg-green-600 shadow-md' : '' }}">
                                            <i class="fas fa-building text-sm text-green-300"></i>
                                            <span class="text-sm font-medium">Kesekretariatan</span>
                                            <svg class="w-3 h-3 ml-1 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        
                                        <!-- Dropdown Content -->
                                        <div class="absolute left-0 mt-1 w-60 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform -translate-y-2 group-hover:translate-y-0 z-50">
                                            <div class="py-2 bg-white rounded-lg shadow-xl ring-1 ring-black ring-opacity-5">
                                                <div class="px-4 py-2.5 bg-gradient-to-r from-green-600 to-green-500 rounded-t-lg">
                                                    <div class="flex items-center space-x-2">
                                                        <i class="fas fa-building text-sm"></i>
                                                        <span class="text-sm font-semibold">Kesekretariatan</span>
                                                    </div>
                                                </div>
                                                
                                                <div class="space-y-0.5 p-2">
                                                    <a href="{{ route('ptip') }}" class="flex items-center space-x-3 px-3 py-2.5 text-sm text-gray-700 rounded-md hover:bg-green-50 transition-colors duration-150 {{ request()->routeIs('ptip') ? 'bg-green-50 text-green-700 font-medium' : '' }}">
                                                        <i class="fas fa-desktop text-green-600 w-5"></i>
                                                        <span>PTIP</span>
                                                    </a>
                                                    
                                                    <a href="{{ route('umum-keuangan') }}" class="flex items-center space-x-3 px-3 py-2.5 text-sm text-gray-700 rounded-md hover:bg-green-50 transition-colors duration-150 {{ request()->routeIs('umum-keuangan') ? 'bg-green-50 text-green-700 font-medium' : '' }}">
                                                        <i class="fas fa-money-bill-wave text-green-600 w-5"></i>
                                                        <span>Umum & Keuangan</span>
                                                    </a>
                                                    
                                                    <a href="{{ route('kepegawaian') }}" class="flex items-center space-x-3 px-3 py-2.5 text-sm text-gray-700 rounded-md hover:bg-green-50 transition-colors duration-150 {{ request()->routeIs('kepegawaian') ? 'bg-green-50 text-green-700 font-medium' : '' }}">
                                                        <i class="fas fa-users text-green-600 w-5"></i>
                                                        <span>Kepegawaian</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <!-- Menu untuk Admin Biasa -->
                                @php
                                    $bagianMapping = [
                                        'perdata' => ['name' => 'Perdata', 'icon' => 'fa-balance-scale', 'route' => 'perdata'],
                                        'pidana' => ['name' => 'Pidana', 'icon' => 'fa-gavel', 'route' => 'pidana'],
                                        'tipikor' => ['name' => 'Tipikor', 'icon' => 'fa-landmark', 'route' => 'tipikor'],
                                        'phi' => ['name' => 'PHI', 'icon' => 'fa-copyright', 'route' => 'phi'],
                                        'hukum' => ['name' => 'Hukum', 'icon' => 'fa-book', 'route' => 'hukum'],
                                        'ptip' => ['name' => 'PTIP', 'icon' => 'fa-desktop', 'route' => 'ptip'],
                                        'umum_keuangan' => ['name' => 'Umum & Keuangan', 'icon' => 'fa-money-bill-wave', 'route' => 'umum-keuangan'],
                                        'kepegawaian' => ['name' => 'Kepegawaian', 'icon' => 'fa-users', 'route' => 'kepegawaian']
                                    ];
                                    $currentRole = auth()->user()->role;
                                    $currentBagian = $bagianMapping[$currentRole] ?? null;
                                @endphp

                                @if($currentBagian)
                                    <a href="{{ route($currentBagian['route']) }}" 
                                       class="relative px-4 py-2.5 rounded-lg transition-all duration-200 hover:bg-green-600/80 hover:shadow-md {{ request()->routeIs($currentBagian['route']) ? 'bg-green-600 shadow-md' : '' }}">
                                        <div class="flex items-center space-x-2">
                                            <i class="fas {{ $currentBagian['icon'] }} text-sm {{ request()->routeIs($currentBagian['route']) ? 'text-green-100' : 'text-green-300' }}"></i>
                                            <span class="text-sm font-medium">{{ $currentBagian['name'] }}</span>
                                        </div>
                                        @if(request()->routeIs($currentBagian['route']))
                                        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-3/4 h-0.5 bg-green-300 rounded-t-lg"></div>
                                        @endif
                                    </a>
                                @endif
                            @endif
                        </div>

                        <!-- User Profile Dropdown -->
                        <div class="relative group ml-2">
                            <button class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition-all duration-200 hover:bg-green-600/80 hover:shadow-md focus:outline-none">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center text-white shadow-md">
                                        <i class="fas fa-user text-xs"></i>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-sm font-medium leading-none">{{ Str::limit(auth()->user()->name, 15) }}</p>
                                        <p class="text-xs text-green-200 mt-0.5">
                                            @if(auth()->user()->isSuperAdmin())
                                                Super Admin
                                            @else
                                                {{ auth()->user()->bagian_name }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <svg class="w-4 h-4 text-green-300 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            
                            <!-- Dropdown Content -->
                            <div class="absolute right-0 mt-1 w-64 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform -translate-y-2 group-hover:translate-y-0 z-50">
                                <div class="bg-white rounded-lg shadow-xl ring-1 ring-black ring-opacity-5 overflow-hidden">
                                    <!-- User Info -->
                                    <div class="px-4 py-3 bg-gradient-to-r from-green-50 to-white border-b border-gray-100">
                                        <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ auth()->user()->email }}</p>
                                        <div class="mt-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ auth()->user()->isSuperAdmin() ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                                @if(auth()->user()->isSuperAdmin())
                                                    <i class="fas fa-crown mr-1"></i>
                                                    Super Administrator
                                                @else
                                                    <i class="fas fa-user-tie mr-1"></i>
                                                    {{ auth()->user()->bagian_name }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Logout Button -->
                                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                                        @csrf
                                        <button type="submit" 
                                                class="w-full text-left px-4 py-3 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 transition-colors duration-150 flex items-center space-x-2 group">
                                            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center group-hover:bg-red-200 transition-colors duration-150">
                                                <i class="fas fa-sign-out-alt text-red-600 text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="font-medium">Keluar</p>
                                                <p class="text-xs text-gray-500 group-hover:text-red-500">Logout dari sistem</p>
                                            </div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" 
                        class="inline-flex items-center justify-center p-2 rounded-lg text-green-200 hover:text-white hover:bg-green-600/50 focus:outline-none transition-colors duration-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden hidden bg-green-700/95 backdrop-blur-sm rounded-lg mt-2 p-4 shadow-xl ring-1 ring-black ring-opacity-5 transition-all duration-300">
            @auth
                <div class="space-y-1">
                    <!-- Dashboard Mobile -->
                    <a href="{{ route('dashboard') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('dashboard') ? 'bg-green-600' : 'hover:bg-green-600/80' }}">
                        <div class="w-10 h-10 rounded-lg bg-green-600/30 flex items-center justify-center">
                            <i class="fas fa-tachometer-alt text-green-200"></i>
                        </div>
                        <div>
                            <p class="font-medium">Dashboard</p>
                            <p class="text-xs text-green-200">Ringkasan sistem</p>
                        </div>
                    </a>

                    <!-- Cetak Laporan Mobile -->
                    <a href="{{ route('laporan') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('laporan*') ? 'bg-green-600' : 'hover:bg-green-600/80' }}">
                        <div class="w-10 h-10 rounded-lg bg-green-600/30 flex items-center justify-center">
                            <i class="fas fa-print text-green-200"></i>
                        </div>
                        <div>
                            <p class="font-medium">Cetak Laporan</p>
                            <p class="text-xs text-green-200">Export laporan</p>
                        </div>
                    </a>

                    <!-- Evaluasi Kerja Mobile -->
                    <a href="{{ route('evaluasi-kerja.index') }}" 
                       class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs('evaluasi-kerja*') ? 'bg-green-600' : 'hover:bg-green-600/80' }}">
                        <div class="w-10 h-10 rounded-lg bg-green-600/30 flex items-center justify-center">
                            <i class="fas fa-file-word text-green-200"></i>
                        </div>
                        <div>
                            <p class="font-medium">Evaluasi Kerja</p>
                            <p class="text-xs text-green-200">Upload dokumen</p>
                        </div>
                    </a>

                    @if(auth()->user()->isSuperAdmin())
                        <!-- SUPER ADMIN MOBILE MENU -->
                        <div class="pt-2 border-t border-green-600">
                            <p class="px-4 py-2 text-sm font-semibold text-green-200 uppercase tracking-wider">Kepaniteraan</p>
                            <div class="space-y-1 ml-4">
                                <a href="{{ route('perdata') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('perdata') ? 'bg-green-600' : 'hover:bg-green-600/80' }}">
                                    <i class="fas fa-balance-scale text-green-300 w-5"></i>
                                    <span>Perdata</span>
                                </a>
                                <a href="{{ route('pidana') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('pidana') ? 'bg-green-600' : 'hover:bg-green-600/80' }}">
                                    <i class="fas fa-gavel text-green-300 w-5"></i>
                                    <span>Pidana</span>
                                </a>
                                <a href="{{ route('tipikor') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('tipikor') ? 'bg-green-600' : 'hover:bg-green-600/80' }}">
                                    <i class="fas fa-landmark text-green-300 w-5"></i>
                                    <span>Tipikor</span>
                                </a>
                                <a href="{{ route('phi') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('phi') ? 'bg-green-600' : 'hover:bg-green-600/80' }}">
                                    <i class="fas fa-copyright text-green-300 w-5"></i>
                                    <span>PHI</span>
                                </a>
                                <a href="{{ route('hukum') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('hukum') ? 'bg-green-600' : 'hover:bg-green-600/80' }}">
                                    <i class="fas fa-book text-green-300 w-5"></i>
                                    <span>Hukum</span>
                                </a>
                            </div>
                        </div>

                        <div class="pt-2 border-t border-green-600">
                            <p class="px-4 py-2 text-sm font-semibold text-green-200 uppercase tracking-wider">Kesekretariatan</p>
                            <div class="space-y-1 ml-4">
                                <a href="{{ route('ptip') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('ptip') ? 'bg-green-600' : 'hover:bg-green-600/80' }}">
                                    <i class="fas fa-desktop text-green-300 w-5"></i>
                                    <span>PTIP</span>
                                </a>
                                <a href="{{ route('umum-keuangan') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('umum-keuangan') ? 'bg-green-600' : 'hover:bg-green-600/80' }}">
                                    <i class="fas fa-money-bill-wave text-green-300 w-5"></i>
                                    <span>Umum & Keuangan</span>
                                </a>
                                <a href="{{ route('kepegawaian') }}" class="flex items-center space-x-3 px-4 py-2.5 rounded-lg transition-colors duration-200 {{ request()->routeIs('kepegawaian') ? 'bg-green-600' : 'hover:bg-green-600/80' }}">
                                    <i class="fas fa-users text-green-300 w-5"></i>
                                    <span>Kepegawaian</span>
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- ADMIN BIASA MOBILE MENU -->
                        @php
                            $bagianMapping = [
                                'perdata' => ['name' => 'Perdata', 'icon' => 'fa-balance-scale', 'route' => 'perdata'],
                                'pidana' => ['name' => 'Pidana', 'icon' => 'fa-gavel', 'route' => 'pidana'],
                                'tipikor' => ['name' => 'Tipikor', 'icon' => 'fa-landmark', 'route' => 'tipikor'],
                                'phi' => ['name' => 'PHI', 'icon' => 'fa-copyright', 'route' => 'phi'],
                                'hukum' => ['name' => 'Hukum', 'icon' => 'fa-book', 'route' => 'hukum'],
                                'ptip' => ['name' => 'PTIP', 'icon' => 'fa-desktop', 'route' => 'ptip'],
                                'umum_keuangan' => ['name' => 'Umum & Keuangan', 'icon' => 'fa-money-bill-wave', 'route' => 'umum-keuangan'],
                                'kepegawaian' => ['name' => 'Kepegawaian', 'icon' => 'fa-users', 'route' => 'kepegawaian']
                            ];
                            $currentRole = auth()->user()->role;
                            $currentBagian = $bagianMapping[$currentRole] ?? null;
                        @endphp

                        @if($currentBagian)
                            <div class="pt-2 border-t border-green-600">
                                <p class="px-4 py-2 text-sm font-semibold text-green-200 uppercase tracking-wider">Bagian Anda</p>
                                <a href="{{ route($currentBagian['route']) }}" 
                                   class="flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors duration-200 {{ request()->routeIs($currentBagian['route']) ? 'bg-green-600' : 'hover:bg-green-600/80' }}">
                                    <div class="w-10 h-10 rounded-lg bg-green-600/30 flex items-center justify-center">
                                        <i class="fas {{ $currentBagian['icon'] }} text-green-200"></i>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $currentBagian['name'] }}</p>
                                        <p class="text-xs text-green-200">Halaman utama</p>
                                    </div>
                                </a>
                            </div>
                        @endif
                    @endif

                    <!-- User Info Mobile -->
                    <div class="pt-4 border-t border-green-600 mt-4">
                        <div class="px-4 py-3 bg-green-600/30 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-sm"></i>
                                </div>
                                <div>
                                    <p class="font-medium">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-green-200">
                                        @if(auth()->user()->isSuperAdmin())
                                            <i class="fas fa-crown mr-1"></i>Super Administrator
                                        @else
                                            <i class="fas fa-user-tie mr-1"></i>{{ auth()->user()->bagian_name }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logout Mobile -->
                    <div class="pt-2">
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center space-x-3 px-4 py-3 rounded-lg transition-colors duration-200 bg-red-600/20 hover:bg-red-600/30 text-red-200 hover:text-white">
                                <div class="w-10 h-10 rounded-lg bg-red-600/30 flex items-center justify-center">
                                    <i class="fas fa-sign-out-alt"></i>
                                </div>
                                <div class="text-left">
                                    <p class="font-medium">Keluar</p>
                                    <p class="text-xs">Logout dari sistem</p>
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            @endauth
        </div>
    </div>
</nav>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button').addEventListener('click', function(e) {
        e.stopPropagation();
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
        mobileMenu.classList.toggle('opacity-0');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        
        if (!mobileMenu.contains(event.target) && !mobileMenuButton.contains(event.target)) {
            mobileMenu.classList.add('hidden');
        }
    });

    // Close mobile menu when clicking on a link
    document.querySelectorAll('#mobile-menu a').forEach(link => {
        link.addEventListener('click', () => {
            document.getElementById('mobile-menu').classList.add('hidden');
        });
    });

    // Add smooth transitions for dropdowns
    document.querySelectorAll('.group').forEach(group => {
        group.addEventListener('mouseenter', function() {
            const dropdown = this.querySelector('.absolute');
            if (dropdown) {
                dropdown.classList.remove('opacity-0', 'invisible');
                dropdown.classList.add('opacity-100', 'visible');
            }
        });
        
        group.addEventListener('mouseleave', function() {
            const dropdown = this.querySelector('.absolute');
            if (dropdown) {
                dropdown.classList.add('opacity-0', 'invisible');
                dropdown.classList.remove('opacity-100', 'visible');
            }
        });
    });
</script>