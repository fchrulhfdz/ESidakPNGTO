<nav class="sticky top-0 z-50 bg-gradient-to-r from-green-800 to-green-700 text-white shadow-xl relative">  
    <!-- Background pattern -->
    <div class="absolute inset-0 opacity-10 bg-repeat" style="background-image: url('data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' viewBox='0 0 20 20'><path d='M10,2 L18,6 L18,14 L10,18 L2,14 L2,6 Z' fill='white'/></svg>');"></div>
    
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="flex justify-between items-center h-16">
            <!-- Logo/Brand -->
<div class="flex items-center space-x-3">
    <div class="w-10 h-10 flex items-center justify-center">
        <img 
            src="{{ asset('storage/logo/logopn.png') }}" 
            alt="Logo PN Gorontalo"
            class="w-10 h-10 object-contain rounded-full shadow-md bg-white p-1"
        >
    </div>

    <a href="{{ route('dashboard') }}" 
       class="text-xl font-bold tracking-tight hover:text-green-100 transition-colors duration-300">
        E-SIDAK <span class="font-normal">PN Gorontalo</span>
    </a>
</div>


            
            <!-- Desktop Navigation -->
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-1">
                    @auth
                        <!-- Dashboard -->
                        <a href="{{ route('dashboard') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 hover:shadow-md group {{ request()->routeIs('dashboard') ? 'bg-green-600 shadow-md' : '' }}">
                            <div class="flex items-center">
                                <i class="fas fa-tachometer-alt mr-2 text-green-200"></i>
                                <span>Dashboard</span>
                            </div>
                            <!-- Active indicator -->
                            @if(request()->routeIs('dashboard'))
                            <div class="absolute bottom-0 left-0 w-full h-1 bg-green-300 rounded-b-lg"></div>
                            @endif
                        </a>

                        @if(auth()->user()->isSuperAdmin())
                            <!-- ========== SUPER ADMIN MENU ========== -->
                            <!-- Cetak Laporan -->
                            <a href="{{ route('laporan') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 hover:shadow-md group {{ request()->routeIs('laporan*') ? 'bg-green-600 shadow-md' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-print mr-2 text-green-200"></i>
                                    <span>Cetak Laporan</span>
                                </div>
                                @if(request()->routeIs('laporan*'))
                                <div class="absolute bottom-0 left-0 w-full h-1 bg-green-300 rounded-b-lg"></div>
                                @endif
                            </a>
                            
                            <!-- Perkara Dropdown -->
                            <div class="relative group">
                                <button class="px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 hover:shadow-md flex items-center {{ request()->routeIs(['perdata','pidana','tipikor','phi','hukum']) ? 'bg-green-600 shadow-md' : '' }}">
                                    <i class="fas fa-gavel mr-2 text-green-200"></i>
                                    <span>Kepaniteraan</span>
                                    <svg class="w-4 h-4 ml-1 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="absolute hidden group-hover:block bg-white text-gray-800 shadow-xl rounded-lg mt-1 w-56 z-50 overflow-hidden transition-all duration-300 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0">
                                    <div class="px-4 py-2 bg-green-700 text-white font-medium">
                                        <i class="fas fa-gavel mr-2"></i> Jenis Perkara
                                    </div>
                                    <a href="{{ route('perdata') }}" class="block px-4 py-3 hover:bg-green-50 transition-colors duration-200 border-l-4 {{ request()->routeIs('perdata') ? 'border-green-500 bg-green-50' : 'border-transparent' }}">
                                        <div class="flex items-center">
                                            <i class="fas fa-balance-scale mr-2 text-green-600"></i>
                                            <span>Perdata</span>
                                        </div>
                                    </a>
                                    <a href="{{ route('pidana') }}" class="block px-4 py-3 hover:bg-green-50 transition-colors duration-200 border-l-4 {{ request()->routeIs('pidana') ? 'border-green-500 bg-green-50' : 'border-transparent' }}">
                                        <div class="flex items-center">
                                            <i class="fas fa-gavel mr-2 text-green-600"></i>
                                            <span>Pidana</span>
                                        </div>
                                    </a>
                                    <a href="{{ route('tipikor') }}" class="block px-4 py-3 hover:bg-green-50 transition-colors duration-200 border-l-4 {{ request()->routeIs('tipikor') ? 'border-green-500 bg-green-50' : 'border-transparent' }}">
                                        <div class="flex items-center">
                                            <i class="fas fa-landmark mr-2 text-green-600"></i>
                                            <span>Tipikor</span>
                                        </div>
                                    </a>
                                    <a href="{{ route('phi') }}" class="block px-4 py-3 hover:bg-green-50 transition-colors duration-200 border-l-4 {{ request()->routeIs('phi') ? 'border-green-500 bg-green-50' : 'border-transparent' }}">
                                        <div class="flex items-center">
                                            <i class="fas fa-copyright mr-2 text-green-600"></i>
                                            <span>PHI</span>
                                        </div>
                                    </a>
                                    <a href="{{ route('hukum') }}" class="block px-4 py-3 hover:bg-green-50 transition-colors duration-200 border-l-4 {{ request()->routeIs('hukum') ? 'border-green-500 bg-green-50' : 'border-transparent' }}">
                                        <div class="flex items-center">
                                            <i class="fas fa-book mr-2 text-green-600"></i>
                                            <span>Hukum</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Kesekretariatan Dropdown -->
                            <div class="relative group">
                                <button class="px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 hover:shadow-md flex items-center {{ request()->routeIs(['ptip','umum-keuangan','kepegawaian']) ? 'bg-green-600 shadow-md' : '' }}">
                                    <i class="fas fa-building mr-2 text-green-200"></i>
                                    <span>Kesekretariatan</span>
                                    <svg class="w-4 h-4 ml-1 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="absolute hidden group-hover:block bg-white text-gray-800 shadow-xl rounded-lg mt-1 w-60 z-50 overflow-hidden transition-all duration-300 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0">
                                    <div class="px-4 py-2 bg-green-700 text-white font-medium">
                                        <i class="fas fa-building mr-2"></i> Kesekretariatan
                                    </div>
                                    <a href="{{ route('ptip') }}" class="block px-4 py-3 hover:bg-green-50 transition-colors duration-200 border-l-4 {{ request()->routeIs('ptip') ? 'border-green-500 bg-green-50' : 'border-transparent' }}">
                                        <div class="flex items-center">
                                            <i class="fas fa-desktop mr-2 text-green-600"></i>
                                            <span>PTIP</span>
                                        </div>
                                    </a>
                                    <a href="{{ route('umum-keuangan') }}" class="block px-4 py-3 hover:bg-green-50 transition-colors duration-200 border-l-4 {{ request()->routeIs('umum-keuangan') ? 'border-green-500 bg-green-50' : 'border-transparent' }}">
                                        <div class="flex items-center">
                                            <i class="fas fa-money-bill-wave mr-2 text-green-600"></i>
                                            <span>Umum & Keuangan</span>
                                        </div>
                                    </a>
                                    <a href="{{ route('kepegawaian') }}" class="block px-4 py-3 hover:bg-green-50 transition-colors duration-200 border-l-4 {{ request()->routeIs('kepegawaian') ? 'border-green-500 bg-green-50' : 'border-transparent' }}">
                                        <div class="flex items-center">
                                            <i class="fas fa-users mr-2 text-green-600"></i>
                                            <span>Kepegawaian</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @else
                            <!-- ========== ADMIN BIASA MENU ========== -->
                            <a href="{{ route('laporan') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 hover:shadow-md group {{ request()->routeIs('laporan*') ? 'bg-green-600 shadow-md' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-print mr-2 text-green-200"></i>
                                    <span>Cetak Laporan</span>
                                </div>
                                @if(request()->routeIs('laporan*'))
                                <div class="absolute bottom-0 left-0 w-full h-1 bg-green-300 rounded-b-lg"></div>
                                @endif
                            </a>
                            
                            <!-- Menu sesuai bagian admin -->
                            @if(auth()->user()->bagian === 'perdata')
                                <a href="{{ route('perdata') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 hover:shadow-md group {{ request()->routeIs('perdata') ? 'bg-green-600 shadow-md' : '' }}">
                                    <div class="flex items-center">
                                        <i class="fas fa-balance-scale mr-2 text-green-200"></i>
                                        <span>Perdata</span>
                                    </div>
                                    @if(request()->routeIs('perdata'))
                                    <div class="absolute bottom-0 left-0 w-full h-1 bg-green-300 rounded-b-lg"></div>
                                    @endif
                                </a>
                            @elseif(auth()->user()->bagian === 'pidana')
                                <a href="{{ route('pidana') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 hover:shadow-md group {{ request()->routeIs('pidana') ? 'bg-green-600 shadow-md' : '' }}">
                                    <div class="flex items-center">
                                        <i class="fas fa-gavel mr-2 text-green-200"></i>
                                        <span>Pidana</span>
                                    </div>
                                    @if(request()->routeIs('pidana'))
                                    <div class="absolute bottom-0 left-0 w-full h-1 bg-green-300 rounded-b-lg"></div>
                                    @endif
                                </a>
                            @elseif(auth()->user()->bagian === 'tipikor')
                                <a href="{{ route('tipikor') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 hover:shadow-md group {{ request()->routeIs('tipikor') ? 'bg-green-600 shadow-md' : '' }}">
                                    <div class="flex items-center">
                                        <i class="fas fa-landmark mr-2 text-green-200"></i>
                                        <span>Tipikor</span>
                                    </div>
                                    @if(request()->routeIs('tipikor'))
                                    <div class="absolute bottom-0 left-0 w-full h-1 bg-green-300 rounded-b-lg"></div>
                                    @endif
                                </a>
                            @elseif(auth()->user()->bagian === 'phi')
                                <a href="{{ route('phi') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 hover:shadow-md group {{ request()->routeIs('phi') ? 'bg-green-600 shadow-md' : '' }}">
                                    <div class="flex items-center">
                                        <i class="fas fa-copyright mr-2 text-green-200"></i>
                                        <span>PHI</span>
                                    </div>
                                    @if(request()->routeIs('phi'))
                                    <div class="absolute bottom-0 left-0 w-full h-1 bg-green-300 rounded-b-lg"></div>
                                    @endif
                                </a>
                            @elseif(auth()->user()->bagian === 'hukum')
                                <a href="{{ route('hukum') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 hover:shadow-md group {{ request()->routeIs('hukum') ? 'bg-green-600 shadow-md' : '' }}">
                                    <div class="flex items-center">
                                        <i class="fas fa-book mr-2 text-green-200"></i>
                                        <span>Hukum</span>
                                    </div>
                                    @if(request()->routeIs('hukum'))
                                    <div class="absolute bottom-0 left-0 w-full h-1 bg-green-300 rounded-b-lg"></div>
                                    @endif
                                </a>
                            @elseif(auth()->user()->bagian === 'ptip')
                                <a href="{{ route('ptip') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 hover:shadow-md group {{ request()->routeIs('ptip') ? 'bg-green-600 shadow-md' : '' }}">
                                    <div class="flex items-center">
                                        <i class="fas fa-desktop mr-2 text-green-200"></i>
                                        <span>PTIP</span>
                                    </div>
                                    @if(request()->routeIs('ptip'))
                                    <div class="absolute bottom-0 left-0 w-full h-1 bg-green-300 rounded-b-lg"></div>
                                    @endif
                                </a>
                            @elseif(auth()->user()->bagian === 'umum_keuangan')
                                <a href="{{ route('umum-keuangan') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 hover:shadow-md group {{ request()->routeIs('umum-keuangan') ? 'bg-green-600 shadow-md' : '' }}">
                                    <div class="flex items-center">
                                        <i class="fas fa-money-bill-wave mr-2 text-green-200"></i>
                                        <span>Umum & Keuangan</span>
                                    </div>
                                    @if(request()->routeIs('umum-keuangan'))
                                    <div class="absolute bottom-0 left-0 w-full h-1 bg-green-300 rounded-b-lg"></div>
                                    @endif
                                </a>
                            @elseif(auth()->user()->bagian === 'kepegawaian')
                                <a href="{{ route('kepegawaian') }}" class="relative px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 hover:shadow-md group {{ request()->routeIs('kepegawaian') ? 'bg-green-600 shadow-md' : '' }}">
                                    <div class="flex items-center">
                                        <i class="fas fa-users mr-2 text-green-200"></i>
                                        <span>Kepegawaian</span>
                                    </div>
                                    @if(request()->routeIs('kepegawaian'))
                                    <div class="absolute bottom-0 left-0 w-full h-1 bg-green-300 rounded-b-lg"></div>
                                    @endif
                                </a>
                            @endif
                        @endif
                        
                        <!-- User Info & Logout -->
                        <div class="relative group ml-2">
                            <button class="flex items-center space-x-2 px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 hover:shadow-md">
                                <div class="w-8 h-8 bg-green-600 rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-user text-sm"></i>
                                </div>
                                <span>{{ auth()->user()->name }}</span>
                                <svg class="w-4 h-4 transition-transform duration-300 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div class="absolute right-0 hidden group-hover:block bg-white text-gray-800 shadow-xl rounded-lg mt-1 w-48 z-50 overflow-hidden transition-all duration-300 opacity-0 group-hover:opacity-100 transform translate-y-2 group-hover:translate-y-0">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                                </div>
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-3 text-sm hover:bg-red-50 text-red-600 transition-colors duration-200 flex items-center">
                                        <i class="fas fa-sign-out-alt mr-2"></i>
                                        <span>Logout</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
            
            <!-- Mobile menu button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-white hover:bg-green-600 p-2 rounded-lg transition-colors duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="md:hidden hidden bg-green-700 rounded-lg mt-2 p-4 shadow-lg transition-all duration-300">
            @auth
                <div class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="block px-4 py-3 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('dashboard') ? 'bg-green-600' : '' }}">
                        <div class="flex items-center">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            <span>Dashboard</span>
                        </div>
                    </a>

                    @if(auth()->user()->isSuperAdmin())
                        <!-- SUPER ADMIN MOBILE MENU -->
                        <a href="{{ route('laporan') }}" class="block px-4 py-3 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('laporan*') ? 'bg-green-600' : '' }}">
                            <div class="flex items-center">
                                <i class="fas fa-print mr-3"></i>
                                <span>Cetak Laporan</span>
                            </div>
                        </a>
                        
                        <!-- Perkara Mobile Dropdown -->
                        <div class="mt-2">
                            <div class="px-4 py-3 rounded-lg bg-green-600">
                                <div class="flex items-center">
                                    <i class="fas fa-gavel mr-3"></i>
                                    <span>Perkara</span>
                                </div>
                            </div>
                            <div class="ml-4 mt-1 space-y-1">
                                <a href="{{ route('perdata') }}" class="block px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('perdata') ? 'bg-green-600' : '' }}">
                                    Perdata
                                </a>
                                <a href="{{ route('pidana') }}" class="block px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('pidana') ? 'bg-green-600' : '' }}">
                                    Pidana
                                </a>
                                <a href="{{ route('tipikor') }}" class="block px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('tipikor') ? 'bg-green-600' : '' }}">
                                    Tipikor
                                </a>
                                <a href="{{ route('phi') }}" class="block px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('phi') ? 'bg-green-600' : '' }}">
                                    PHI
                                </a>
                                <a href="{{ route('hukum') }}" class="block px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('hukum') ? 'bg-green-600' : '' }}">
                                    Hukum
                                </a>
                            </div>
                        </div>
                        
                        <!-- Kesekretariatan Mobile Dropdown -->
                        <div class="mt-2">
                            <div class="px-4 py-3 rounded-lg bg-green-600">
                                <div class="flex items-center">
                                    <i class="fas fa-building mr-3"></i>
                                    <span>Kesekretariatan</span>
                                </div>
                            </div>
                            <div class="ml-4 mt-1 space-y-1">
                                <a href="{{ route('ptip') }}" class="block px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('ptip') ? 'bg-green-600' : '' }}">
                                    PTIP
                                </a>
                                <a href="{{ route('umum-keuangan') }}" class="block px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('umum-keuangan') ? 'bg-green-600' : '' }}">
                                    Umum & Keuangan
                                </a>
                                <a href="{{ route('kepegawaian') }}" class="block px-4 py-2 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('kepegawaian') ? 'bg-green-600' : '' }}">
                                    Kepegawaian
                                </a>
                            </div>
                        </div>
                    @else
                        <!-- ADMIN BIASA MOBILE MENU -->
                        <a href="{{ route('laporan') }}" class="block px-4 py-3 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('laporan*') ? 'bg-green-600' : '' }}">
                            <div class="flex items-center">
                                <i class="fas fa-print mr-3"></i>
                                <span>Cetak Laporan</span>
                            </div>
                        </a>
                        
                        <!-- Menu sesuai bagian admin -->
                        @if(auth()->user()->bagian === 'perdata')
                            <a href="{{ route('perdata') }}" class="block px-4 py-3 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('perdata') ? 'bg-green-600' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-balance-scale mr-3"></i>
                                    <span>Perdata</span>
                                </div>
                            </a>
                        @elseif(auth()->user()->bagian === 'pidana')
                            <a href="{{ route('pidana') }}" class="block px-4 py-3 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('pidana') ? 'bg-green-600' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-gavel mr-3"></i>
                                    <span>Pidana</span>
                                </div>
                            </a>
                        @elseif(auth()->user()->bagian === 'tipikor')
                            <a href="{{ route('tipikor') }}" class="block px-4 py-3 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('tipikor') ? 'bg-green-600' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-landmark mr-3"></i>
                                    <span>Tipikor</span>
                                </div>
                            </a>
                        @elseif(auth()->user()->bagian === 'phi')
                            <a href="{{ route('phi') }}" class="block px-4 py-3 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('phi') ? 'bg-green-600' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-copyright mr-3"></i>
                                    <span>PHI</span>
                                </div>
                            </a>
                        @elseif(auth()->user()->bagian === 'hukum')
                            <a href="{{ route('hukum') }}" class="block px-4 py-3 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('hukum') ? 'bg-green-600' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-book mr-3"></i>
                                    <span>Hukum</span>
                                </div>
                            </a>
                        @elseif(auth()->user()->bagian === 'ptip')
                            <a href="{{ route('ptip') }}" class="block px-4 py-3 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('ptip') ? 'bg-green-600' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-desktop mr-3"></i>
                                    <span>PTIP</span>
                                </div>
                            </a>
                        @elseif(auth()->user()->bagian === 'umum_keuangan')
                            <a href="{{ route('umum-keuangan') }}" class="block px-4 py-3 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('umum-keuangan') ? 'bg-green-600' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-money-bill-wave mr-3"></i>
                                    <span>Umum & Keuangan</span>
                                </div>
                            </a>
                        @elseif(auth()->user()->bagian === 'kepegawaian')
                            <a href="{{ route('kepegawaian') }}" class="block px-4 py-3 rounded-lg transition-all duration-300 hover:bg-green-600 {{ request()->routeIs('kepegawaian') ? 'bg-green-600' : '' }}">
                                <div class="flex items-center">
                                    <i class="fas fa-users mr-3"></i>
                                    <span>Kepegawaian</span>
                                </div>
                            </a>
                        @endif
                    @endif
                    
                    <!-- Logout Mobile -->
                    <div class="pt-2 border-t border-green-600 mt-4">
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 rounded-lg transition-all duration-300 hover:bg-red-600 flex items-center">
                                <i class="fas fa-sign-out-alt mr-3"></i>
                                <span>Logout</span>
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
    document.getElementById('mobile-menu-button').addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
</script>