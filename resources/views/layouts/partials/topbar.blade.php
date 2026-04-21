<!-- Topbar -->
<header class="bg-white border-b shadow-sm border-gray-200/50 sticky top-0 z-10 h-16 flex items-center justify-between px-4 sm:px-6 lg:px-8">
    
    <!-- Sisi Kiri -->
    <div class="flex items-center">
        <!-- Hamburger Menu -->
        <button @click="toggleSidebar()" class="p-2 mr-4 text-gray-500 rounded-md hover:bg-gray-100 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-[#C8A84B]">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <!-- Breadcrumb (Desktop Only) -->
        <nav class="hidden md:flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
                <li>
                    <a href="{{ route('dashboard') ?? '#' }}" class="text-sm font-medium text-gray-500 hover:text-[#1B3A2D]">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span class="ml-2 text-sm font-medium text-gray-800">Modul</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Sisi Kanan -->
    <div class="flex items-center space-x-4 sm:space-x-6">
        
        <!-- Real-time Clock (Desktop/Tablet) -->
        <div class="hidden text-sm font-medium text-gray-600 sm:block" x-data="{ time: '' }" x-init="
            setInterval(() => {
                const now = new Date();
                const options = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
                const datePart = now.toLocaleDateString('id-ID', options);
                const timePart = now.toLocaleTimeString('id-ID');
                time = `${datePart} | ${timePart} WIB`;
            }, 1000);
        ">
            <span x-text="time">Memuat waktu...</span>
        </div>

        <!-- Notification Dropdown -->
        <livewire:notification-dropdown />

        <div class="h-6 w-px bg-gray-300"></div>

        <!-- User Profile Dropdown -->
        <div class="relative" x-data="{ open: false }" @click.away="open = false">
            <button @click="open = !open" class="flex items-center space-x-2 focus:outline-none">
                <div class="w-8 h-8 rounded-full bg-[#1B3A2D] text-white flex items-center justify-center font-bold text-sm uppercase overflow-hidden">
                    @if(auth()->user()->detail && auth()->user()->detail->avatar)
                        <img src="{{ Storage::url(auth()->user()->detail->avatar) }}" class="w-full h-full object-cover">
                    @else
                        {{ substr(Auth::user()->name ?? 'U', 0, 1) }}
                    @endif
                </div>
                <div class="hidden md:flex flex-col items-start overflow-hidden w-32">
                    <span class="text-sm font-semibold text-gray-700 truncate w-full">{{ Auth::user()->name ?? 'User' }}</span>
                </div>
                <svg class="hidden md:block w-4 h-4 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Panel -->
            <div x-show="open" x-transition.origin.top.right class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
                <span class="block px-4 py-2 text-xs text-gray-500 border-b border-gray-100 md:hidden">{{ Auth::user()->name ?? 'User' }}</span>
                
                <a href="{{ route('profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>
                <a href="{{ route('profile.index') }}#security" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Ganti Password</a>
                <div class="border-t border-gray-100"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-medium">Keluar</button>
                </form>
            </div>
        </div>
    </div>
</header>
