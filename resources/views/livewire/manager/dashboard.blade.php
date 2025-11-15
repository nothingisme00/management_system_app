<div class="space-y-6">
    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Manager</h1>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Selamat datang, {{ auth()->user()->name }}</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3">
        {{-- Team Members --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
            <div class="space-y-1">
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Anggota Tim</h3>
                <div class="flex items-baseline space-x-2">
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">0</p>
                    <span class="text-sm text-gray-600 dark:text-gray-400">Orang</span>
                </div>
            </div>
        </div>

        {{-- Active Projects --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-teal-50 dark:bg-teal-900/20 rounded-lg">
                    <svg class="w-6 h-6 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
            </div>
            <div class="space-y-1">
                <h3 class="text-sm font-medium text-gray-600 dark:text-gray-400">Proyek Aktif</h3>
                <div class="flex items-baseline space-x-2">
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">0</p>
                    <span class="text-sm text-teal-600 dark:text-teal-400">Berjalan</span>
                </div>
            </div>
        </div>

        {{-- User Info --}}
        <div class="bg-gradient-to-br from-purple-600 to-pink-600 rounded-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/10 backdrop-blur-sm rounded-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            <div class="space-y-1">
                <h3 class="text-sm font-medium text-white/80">Akun Anda</h3>
                <p class="text-xl font-bold">{{ auth()->user()->name }}</p>
                <p class="text-sm text-white/90">Role: Manager</p>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Manager Actions</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <button class="flex items-center justify-center space-x-2 px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="font-medium">Kelola Tim</span>
            </button>

            <button class="flex items-center justify-center space-x-2 px-4 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                <span class="font-medium">Tracking Proyek</span>
            </button>

            <button class="flex items-center justify-center space-x-2 px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition dark:bg-gray-700 dark:hover:bg-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium">Persetujuan</span>
            </button>

            <button class="flex items-center justify-center space-x-2 px-4 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition dark:bg-gray-700 dark:hover:bg-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span class="font-medium">Laporan</span>
            </button>
        </div>
    </div>
</div>
