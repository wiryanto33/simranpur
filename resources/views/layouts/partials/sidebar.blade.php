<!-- Mobile sidebar backdrop -->
<div x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-20 bg-gray-900/50 lg:hidden" @click="sidebarOpen = false"></div>

<!-- Sidebar -->
<aside :class="sidebarCollapsed ? 'w-16' : 'w-64'" class="fixed inset-y-0 left-0 z-30 flex flex-col pt-0 transition-all duration-300 transform bg-[#1B3A2D] lg:static lg:translate-x-0" :class="{'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen}">
    
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between h-16 px-4 bg-[#1B3A2D] shadow-sm">
        <div class="flex items-center space-x-3 overflow-hidden">
            <div class="flex items-center justify-center flex-shrink-0 w-8 h-8">
                <img src="{{ asset('logo/marinir.png') }}" alt="Logo Marinir" class="object-contain w-full h-full drop-shadow-md">
            </div>
            <div class="flex flex-col flex-1 min-w-0" x-show="!sidebarCollapsed" x-transition>
                <h1 class="text-lg font-bold text-white truncate">SIMRANPUR</h1>
                <p class="text-xs font-medium text-[#C8A84B] truncate">Korps Marinir TNI AL</p>
            </div>
        </div>
    </div>
    
    <!-- Separator -->
    <div class="h-px bg-[#C8A84B]/30 mx-4 my-2"></div>

    <!-- Profil User di Sidebar -->
    <div class="px-4 py-3" x-show="!sidebarCollapsed" x-transition>
        <div class="flex items-center space-x-3">
            <div class="flex items-center justify-center w-10 h-10 font-bold text-[#1B3A2D] uppercase bg-[#C8A84B] rounded-full shrink-0 overflow-hidden">
                @if(auth()->user()->detail && auth()->user()->detail->avatar)
                    <img src="{{ Storage::url(auth()->user()->detail->avatar) }}" class="w-full h-full object-cover">
                @else
                    {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                @endif
            </div>
            <div class="flex flex-col overflow-hidden">
                <span class="text-sm font-semibold text-white truncate">{{ Auth::user()->name ?? 'User Name' }}</span>
                <div class="mt-1">
                    @php
                        $role = Auth::user() ? Auth::user()->roles->first()?->name : 'Operator';
                        $roleColor = match($role) {
                            'Admin' => 'bg-red-500 text-white',
                            'Komandan' => 'bg-[#C8A84B] text-[#1B3A2D]',
                            'KepMek' => 'bg-blue-500 text-white',
                            'Mekanik' => 'bg-green-500 text-white',
                            'Logistik' => 'bg-purple-500 text-white',
                            default => 'bg-gray-500 text-white'
                        };
                    @endphp
                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded {{ $roleColor }}">
                        {{ $role }}
                    </span>
                </div>
            </div>
        </div>
    </div>
    <div x-show="sidebarCollapsed" class="flex justify-center py-4" x-transition>
        <div class="flex items-center justify-center w-8 h-8 font-bold text-[#1B3A2D] uppercase bg-[#C8A84B] rounded-full">
            {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
        </div>
    </div>

    <!-- Navigasi -->
    <nav class="flex-1 px-3 mt-4 space-y-1 overflow-y-auto">
        <!-- Grup Utama -->
        <div class="mb-4" x-data="{ open: true }">
            <p x-show="!sidebarCollapsed" class="px-3 mb-2 text-xs font-semibold tracking-wider text-[#C8A84B] uppercase">Utama</p>
            
            <a href="{{ route('dashboard') ?? '#' }}" class="relative flex items-center px-3 py-2 text-sm font-medium transition-colors rounded-md {{ request()->routeIs('dashboard') ? 'bg-[#2D5A45] text-white border-l-4 border-[#C8A84B]' : 'text-gray-300 hover:bg-[#2D5A45] hover:text-white border-l-4 border-transparent' }}" title="Dashboard">
                <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ request()->routeIs('dashboard') ? 'text-[#C8A84B]' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <span x-show="!sidebarCollapsed" class="truncate">Dashboard</span>
            </a>
        </div>

        <!-- Grup Operasional -->
        <div class="mb-4">
            <p x-show="!sidebarCollapsed" class="px-3 mb-2 text-xs font-semibold tracking-wider text-[#C8A84B] uppercase">Operasional</p>
            
            @can('view_kendaraan')
            <a href="{{ route('kendaraan.index') }}" class="relative flex items-center px-3 py-2 text-sm font-medium transition-colors rounded-md {{ request()->routeIs('kendaraan.*') ? 'bg-[#2D5A45] text-white border-l-4 border-[#C8A84B]' : 'text-gray-300 hover:bg-[#2D5A45] hover:text-white border-l-4 border-transparent' }}" title="Kendaraan Tempur">
                <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ request()->routeIs('kendaraan.*') ? 'text-[#C8A84B]' : 'text-gray-400 group-hover:text-white' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                </svg>
                <span x-show="!sidebarCollapsed" class="truncate">Kendaraan Tempur</span>
            </a>
            @endcan

            @can('view_jadwal_pemeliharaan')
            <a href="{{ route('jadwal.index') }}" class="relative flex items-center px-3 py-2 text-sm font-medium text-gray-300 transition-colors border-l-4 {{ request()->routeIs('jadwal.*') ? 'border-[#C8A84B] bg-[#2D5A45] text-white' : 'border-transparent hover:bg-[#2D5A45] hover:text-white' }} rounded-md" title="Jadwal Pemeliharaan">
                <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ request()->routeIs('jadwal.*') ? 'text-[#C8A84B]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span x-show="!sidebarCollapsed" class="truncate">Jadwal Pemeliharaan</span>
            </a>
            @endcan
        </div>

        {{-- Grup Laporan & Rekap: tampil jika punya minimal 1 permission laporan --}}
        @if(
            auth()->user()->can('view_laporan_rekap_kesiapan') ||
            auth()->user()->can('view_laporan_rekap_pemeliharaan') ||
            auth()->user()->can('view_laporan_rekap_kerusakan') ||
            auth()->user()->can('view_laporan_rekap_suku_cadang') ||
            auth()->user()->can('view_laporan_audit_log') ||
            auth()->user()->can('view_laporan_kerusakan') ||
            auth()->user()->can('view_laporan_perbaikan')
        )
        <div class="mb-4" x-data="{ open: {{ request()->routeIs('laporan.*') ? 'true' : 'false' }} }">
            <p x-show="!sidebarCollapsed" class="px-3 mb-2 text-xs font-semibold tracking-wider text-[#C8A84B] uppercase">Analisis &amp; Laporan</p>

            <button @click="open = !open" class="flex items-center justify-between w-full px-3 py-2 text-sm font-medium text-gray-300 transition-colors border-l-4 border-transparent rounded-md hover:bg-[#2D5A45] hover:text-white group">
                <div class="flex items-center">
                    <svg class="flex-shrink-0 w-5 h-5 mr-3 text-gray-400 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate">Rekap &amp; Laporan</span>
                </div>
                <svg x-show="!sidebarCollapsed" :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            {{-- Sub-menu Rekap Statistik --}}
            <div x-show="open && !sidebarCollapsed" x-transition class="mt-1 ml-8 space-y-1" style="display: none;">

                @can('view_laporan_rekap_kesiapan')
                <a href="{{ route('laporan.kesiapan') }}"
                   class="flex items-center px-3 py-1.5 text-xs font-medium rounded {{ request()->routeIs('laporan.kesiapan') ? 'text-[#C8A84B]' : 'text-gray-400 hover:text-white hover:underline' }}">
                    <svg class="w-3 h-3 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Kesiapan Ranpur
                </a>
                @endcan

                @can('view_laporan_rekap_pemeliharaan')
                <a href="{{ route('laporan.pemeliharaan') }}"
                   class="flex items-center px-3 py-1.5 text-xs font-medium rounded {{ request()->routeIs('laporan.pemeliharaan') ? 'text-[#C8A84B]' : 'text-gray-400 hover:text-white hover:underline' }}">
                    <svg class="w-3 h-3 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Rekap Pemeliharaan
                </a>
                @endcan

                @can('view_laporan_rekap_kerusakan')
                <a href="{{ route('laporan.kerusakan') }}"
                   class="flex items-center px-3 py-1.5 text-xs font-medium rounded {{ request()->routeIs('laporan.kerusakan') ? 'text-[#C8A84B]' : 'text-gray-400 hover:text-white hover:underline' }}">
                    <svg class="w-3 h-3 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Kerusakan &amp; Perbaikan
                </a>
                @endcan

                @can('view_laporan_rekap_suku_cadang')
                <a href="{{ route('laporan.suku-cadang') }}"
                   class="flex items-center px-3 py-1.5 text-xs font-medium rounded {{ request()->routeIs('laporan.suku-cadang') ? 'text-[#C8A84B]' : 'text-gray-400 hover:text-white hover:underline' }}">
                    <svg class="w-3 h-3 mr-2 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    Penggunaan Suku Cadang
                </a>
                @endcan

            </div>

            {{-- Sub-menu Laporan Operasional (CRUD laporan) --}}
            @if(auth()->user()->can('view_laporan_kerusakan') || auth()->user()->can('view_laporan_perbaikan'))
            <div class="mt-2 border-t border-[#C8A84B]/20 pt-2">
                @can('view_laporan_kerusakan')
                <a href="{{ route('laporan-kerusakan.index') }}"
                   class="relative flex items-center px-3 py-2 text-sm font-medium text-gray-400 transition-colors border-l-4 {{ request()->routeIs('laporan-kerusakan.*') ? 'border-[#C8A84B] bg-[#2D5A45] text-white' : 'border-transparent hover:bg-[#2D5A45] hover:text-white' }} rounded-md"
                   title="Lap. Kerusakan">
                    <svg class="flex-shrink-0 w-4 h-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate text-xs">Laporan Kerusakan</span>
                </a>
                @endcan

                @can('view_laporan_perbaikan')
                <a href="{{ route('laporan-perbaikan.index') }}"
                   class="relative flex items-center px-3 py-2 mt-1 text-sm font-medium text-gray-400 transition-colors border-l-4 {{ request()->routeIs('laporan-perbaikan.*') ? 'border-[#C8A84B] bg-[#2D5A45] text-white' : 'border-transparent hover:bg-[#2D5A45] hover:text-white' }} rounded-md"
                   title="Lap. Perbaikan">
                    <svg class="flex-shrink-0 w-4 h-4 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="truncate text-xs">Laporan Perbaikan</span>
                </a>
                @endcan
            </div>
            @endif

        </div>
        @endif

        <!-- Grup Logistik -->
        @can('view_suku_cadang')
        <div class="mb-4">
            <p x-show="!sidebarCollapsed" class="px-3 mb-2 text-xs font-semibold tracking-wider text-[#C8A84B] uppercase">Logistik</p>
            
            <a href="{{ route('suku-cadang.index') }}" class="relative flex items-center px-3 py-2 text-sm font-medium text-gray-300 transition-colors border-l-4 {{ request()->routeIs('suku-cadang.*') ? 'border-[#C8A84B] bg-[#2D5A45] text-white' : 'border-transparent hover:bg-[#2D5A45] hover:text-white' }} rounded-md" title="Suku Cadang">
                <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ request()->routeIs('suku-cadang.*') ? 'text-[#C8A84B]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <span x-show="!sidebarCollapsed" class="truncate">Gudang Suku Cadang</span>
                @php $scMenipis = \App\Models\SukuCadang::whereColumn('stok','<=','stok_minimum')->count(); @endphp
                @if($scMenipis > 0)
                <span x-show="!sidebarCollapsed" class="ml-auto inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-orange-500 rounded-full">{{ $scMenipis }}</span>
                @endif
            </a>

            @can('view_permintaan_suku_cadang')
            <a href="{{ route('permintaan-suku-cadang.index') }}" class="relative flex items-center px-3 py-2 mt-1 text-sm font-medium text-gray-300 transition-colors border-l-4 {{ request()->routeIs('permintaan-suku-cadang.*') ? 'border-[#C8A84B] bg-[#2D5A45] text-white' : 'border-transparent hover:bg-[#2D5A45] hover:text-white' }} rounded-md" title="Permintaan">
                <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ request()->routeIs('permintaan-suku-cadang.*') ? 'text-[#C8A84B]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span x-show="!sidebarCollapsed" class="truncate">Permintaan Suku Cadang</span>
                @php $pendingReq = \App\Models\PermintaanSukuCadang::where('status', 'Pending')->count(); @endphp
                @if($pendingReq > 0)
                <span x-show="!sidebarCollapsed" class="ml-auto inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold leading-none text-white bg-red-500 rounded-full">{{ $pendingReq }}</span>
                @endif
            </a>
            @endcan
        </div>
        @endcan
        
        <!-- Grup Sistem -->
        @role('Admin')
        <div class="mb-4">
            <p x-show="!sidebarCollapsed" class="px-3 mb-2 text-xs font-semibold tracking-wider text-[#C8A84B] uppercase">Sistem</p>
            
            <a href="{{ route('kompi.index') }}" class="relative flex items-center px-3 py-2 text-sm font-medium text-gray-300 transition-colors border-l-4 {{ request()->routeIs('kompi.*') ? 'border-[#C8A84B] bg-[#2D5A45] text-white' : 'border-transparent hover:bg-[#2D5A45] hover:text-white' }} rounded-md" title="Data Kompi">
                <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ request()->routeIs('kompi.*') ? 'text-[#C8A84B]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span x-show="!sidebarCollapsed" class="truncate">Data Kompi</span>
            </a>

            <a href="{{ route('user.index') }}" class="relative flex items-center px-3 py-2 text-sm font-medium text-gray-300 transition-colors border-l-4 {{ request()->routeIs('user.*') ? 'border-[#C8A84B] bg-[#2D5A45] text-white' : 'border-transparent hover:bg-[#2D5A45] hover:text-white' }} rounded-md" title="Manajemen User">
                <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ request()->routeIs('user.*') ? 'text-[#C8A84B]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span x-show="!sidebarCollapsed" class="truncate">Manajemen User</span>
            </a>

            <a href="#" class="relative flex items-center px-3 py-2 text-sm font-medium text-gray-300 transition-colors border-l-4 border-transparent rounded-md hover:bg-[#2D5A45] hover:text-white" title="Pengaturan">
                <svg class="flex-shrink-0 w-5 h-5 mr-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span x-show="!sidebarCollapsed" class="truncate">Pengaturan</span>
            </a>

            <a href="{{ route('laporan.audit-log') }}" class="relative flex items-center px-3 py-2 text-sm font-medium text-gray-300 transition-colors border-l-4 {{ request()->routeIs('laporan.audit-log') ? 'border-[#C8A84B] bg-[#2D5A45] text-white' : 'border-transparent hover:bg-[#2D5A45] hover:text-white' }} rounded-md" title="Audit Log">
                <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ request()->routeIs('laporan.audit-log') ? 'text-[#C8A84B]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l2 2 4-4" />
                </svg>
                <span x-show="!sidebarCollapsed" class="truncate">Audit Log</span>
            </a>

            <a href="{{ route('role.index') }}" class="relative flex items-center px-3 py-2 text-sm font-medium text-gray-300 transition-colors border-l-4 {{ request()->routeIs('role.*') ? 'border-[#C8A84B] bg-[#2D5A45] text-white' : 'border-transparent hover:bg-[#2D5A45] hover:text-white' }} rounded-md" title="Role & Permission">
                <svg class="flex-shrink-0 w-5 h-5 mr-3 {{ request()->routeIs('role.*') ? 'text-[#C8A84B]' : 'text-gray-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                <span x-show="!sidebarCollapsed" class="truncate">Role & Permission</span>
            </a>
        </div>
        @endrole

    </nav>

    <!-- Footer Sidebar -->
    <div class="flex-shrink-0 p-4 border-t border-[#C8A84B]/30 bg-[#1B3A2D]">
        <div class="flex items-center justify-between">
            <button @click="toggleSidebar()" class="hidden text-gray-400 transition hover:text-white lg:block" :title="sidebarCollapsed ? 'Expand Sidebar' : 'Collapse Sidebar'">
                <svg x-show="!sidebarCollapsed" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
                <svg x-show="sidebarCollapsed" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
                </svg>
            </button>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="flex items-center justify-center w-full px-3 py-2 text-sm font-medium text-red-200 transition bg-red-900 rounded-md hover:bg-red-800 hover:text-white group">
                    <svg class="w-5 h-5 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span x-show="!sidebarCollapsed" class="ml-3 truncate">Keluar</span>
                </button>
            </form>
        </div>
    </div>
</aside>
