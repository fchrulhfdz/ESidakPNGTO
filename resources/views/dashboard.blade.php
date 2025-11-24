@extends('layouts.app')

@section('title', 'Dashboard - E-SIDAK')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-8">
    <div class="container mx-auto px-4">
        <!-- Header Section -->
        <div class="mb-8 text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-2">Dashboard E-SIDAK</h1>
            <p class="text-lg text-gray-600">Pengadilan Negeri Gorontalo</p>
            <div class="w-24 h-1 bg-gradient-to-r from-green-500 to-blue-500 mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- Welcome Card -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 border-l-4 border-green-500 transform transition-transform duration-300 hover:translate-y-1">
            <div class="flex items-center">
                <div class="bg-gradient-to-br from-green-500 to-green-600 p-4 rounded-2xl shadow-md">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="ml-6">
                    <h2 class="text-2xl font-bold text-gray-800">
                        Selamat Datang, {{ auth()->user()->name }}
                    </h2>
                    <p class="text-gray-600 mt-1">
                        @if(auth()->user()->isSuperAdmin())
                            Anda login sebagai <span class="font-semibold text-green-600">Super Administrator</span>
                        @else
                            Anda login sebagai <span class="font-semibold text-green-600">Admin {{ ucfirst(str_replace('_', ' ', auth()->user()->bagian)) }}</span>
                        @endif
                    </p>
                </div>
                <div class="ml-auto bg-green-50 text-green-700 px-4 py-2 rounded-lg border border-green-200">
                    <div class="text-sm font-medium">Status Sistem</div>
                    <div class="flex items-center mt-1">
                        <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                        <span class="text-xs">Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-2xl shadow-lg p-6 transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-t-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Perkara</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">0</p>
                        <div class="flex items-center mt-2">
                            <svg class="w-4 h-4 text-green-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span class="text-xs text-green-600">Data terbaru</span>
                        </div>
                    </div>
                    <div class="bg-green-100 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-t-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Laporan Tersedia</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">0</p>
                        <div class="flex items-center mt-2">
                            <svg class="w-4 h-4 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span class="text-xs text-blue-600">Siap dicetak</span>
                        </div>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-t-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Realisasi Rata-rata</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">0%</p>
                        <div class="flex items-center mt-2">
                            <svg class="w-4 h-4 text-purple-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span class="text-xs text-purple-600">Bulan ini</span>
                        </div>
                    </div>
                    <div class="bg-purple-100 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg p-6 transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1 border-t-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Capaian Rata-rata</p>
                        <p class="text-3xl font-bold text-gray-800 mt-2">0%</p>
                        <div class="flex items-center mt-2">
                            <svg class="w-4 h-4 text-orange-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span class="text-xs text-orange-600">Bulan ini</span>
                        </div>
                    </div>
                    <div class="bg-orange-100 p-3 rounded-xl">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-xl p-8 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-800">Akses Cepat</h3>
                <div class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                    Pilih menu untuk melanjutkan
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @if(auth()->user()->isSuperAdmin() || in_array(auth()->user()->bagian, ['perdata', 'pidana', 'tipikor', 'phi', 'hukum']))
                <a href="{{ auth()->user()->isSuperAdmin() ? route('perdata') : route(auth()->user()->bagian) }}" 
                   class="group bg-gradient-to-br from-green-50 to-green-100 hover:from-green-100 hover:to-green-200 border border-green-200 rounded-2xl p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-green-500 group-hover:bg-green-600 p-4 rounded-2xl shadow-md transition-colors duration-300 mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Input Perkara</h4>
                        <p class="text-sm text-gray-600">Kelola data perkara dan informasi terkait</p>
                        <div class="mt-4 text-green-600 group-hover:text-green-700 transition-colors duration-300">
                            <span class="text-sm font-medium">Akses Sekarang</span>
                            <svg class="w-4 h-4 inline-block ml-1 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
                @endif

                @if(auth()->user()->isSuperAdmin() || in_array(auth()->user()->bagian, ['ptip', 'umum_keuangan', 'kepegawaian']))
                <a href="{{ auth()->user()->isSuperAdmin() ? route('ptip') : route(auth()->user()->bagian) }}" 
                   class="group bg-gradient-to-br from-blue-50 to-blue-100 hover:from-blue-100 hover:to-blue-200 border border-blue-200 rounded-2xl p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-blue-500 group-hover:bg-blue-600 p-4 rounded-2xl shadow-md transition-colors duration-300 mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Kesekretariatan</h4>
                        <p class="text-sm text-gray-600">Kelola administrasi dan kepegawaian</p>
                        <div class="mt-4 text-blue-600 group-hover:text-blue-700 transition-colors duration-300">
                            <span class="text-sm font-medium">Akses Sekarang</span>
                            <svg class="w-4 h-4 inline-block ml-1 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
                @endif

                <a href="{{ route('laporan') }}" 
                   class="group bg-gradient-to-br from-purple-50 to-purple-100 hover:from-purple-100 hover:to-purple-200 border border-purple-200 rounded-2xl p-6 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-lg">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-purple-500 group-hover:bg-purple-600 p-4 rounded-2xl shadow-md transition-colors duration-300 mb-4">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Cetak Laporan</h4>
                        <p class="text-sm text-gray-600">Generate dan cetak laporan periodik</p>
                        <div class="mt-4 text-purple-600 group-hover:text-purple-700 transition-colors duration-300">
                            <span class="text-sm font-medium">Akses Sekarang</span>
                            <svg class="w-4 h-4 inline-block ml-1 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // Animasi untuk statistik counter (placeholder untuk data real)
    document.addEventListener('DOMContentLoaded', function() {
        // Efek hover interaktif
        const cards = document.querySelectorAll('.transform');
        
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.3s ease';
            });
        });

        // Placeholder untuk animasi counter (bisa diisi dengan data real nanti)
        const counters = document.querySelectorAll('.text-3xl');
        counters.forEach(counter => {
            const target = parseInt(counter.textContent);
            if (target > 0) {
                // Animasi counter bisa ditambahkan di sini
            }
        });
    });
</script>
@endsection 