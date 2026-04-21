<div class="py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Profil Pengguna</h2>
        <p class="text-sm text-gray-600">Kelola informasi profil, keamanan akun, dan pantau aktivitas login Anda.</p>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Pengaturan Profil -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informasi Dasar -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-[#1B3A2D]/10 rounded-lg">
                            <svg class="w-5 h-5 text-[#1B3A2D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Informasi Dasar</h3>
                    </div>
                </div>
                <div class="p-6">
                    @if (session()->has('success'))
                        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ session('success') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="updateProfile" class="space-y-6">
                        <div class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-6">
                            <div class="relative group">
                                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-[#C8A84B] bg-gray-100 flex items-center justify-center">
                                    @if ($new_avatar)
                                        <img src="{{ $new_avatar->temporaryUrl() }}" class="w-full h-full object-cover">
                                    @elseif ($avatar)
                                        <img src="{{ Storage::url($avatar) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-3xl font-bold text-[#1B3A2D]">{{ substr($name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <label for="new_avatar" class="absolute bottom-0 right-0 p-1.5 bg-[#1B3A2D] text-white rounded-full border-2 border-white cursor-pointer hover:bg-[#2D5A45] transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <input type="file" id="new_avatar" wire:model="new_avatar" class="hidden" accept="image/*">
                                </label>
                            </div>
                            <div class="flex-1 text-center sm:text-left">
                                <h4 class="text-sm font-medium text-gray-700">Foto Profil</h4>
                                <p class="text-xs text-gray-500 mt-1">Gunakan foto formal dengan seragam TNI AL jika memungkinkan. Format JPG, PNG, atau WEBP (Maks 1MB).</p>
                                @error('new_avatar') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                <input type="text" wire:model="name" class="w-full rounded-lg border-gray-300 focus:ring-[#1B3A2D] focus:border-[#1B3A2D] transition-shadow">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                                <input type="email" wire:model="email" class="w-full rounded-lg border-gray-300 focus:ring-[#1B3A2D] focus:border-[#1B3A2D] transition-shadow">
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end">
                            <button type="submit" wire:loading.attr="disabled" class="px-6 py-2 bg-[#1B3A2D] text-white rounded-lg flex items-center space-x-2 hover:bg-[#2D5A45] transition-all shadow-md active:scale-95">
                                <span wire:loading.remove>Simpan Perubahan</span>
                                <span wire:loading>Memproses...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Keamanan & Password -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-[#1B3A2D]/10 rounded-lg">
                            <svg class="w-5 h-5 text-[#1B3A2D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Keamanan</h3>
                    </div>
                </div>
                <div class="p-6">
                    @if (session()->has('success_password'))
                        <div class="mb-4 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 text-sm flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            {{ session('success_password') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="updatePassword" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                            <input type="password" wire:model="current_password" class="w-full md:w-1/2 rounded-lg border-gray-300 focus:ring-[#1B3A2D] focus:border-[#1B3A2D]">
                            @error('current_password') <span class="text-red-500 text-xs ml-1">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                <input type="password" wire:model="password" class="w-full rounded-lg border-gray-300 focus:ring-[#1B3A2D] focus:border-[#1B3A2D]">
                                @error('password') <span class="text-red-500 text-xs ml-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                                <input type="password" wire:model="password_confirmation" class="w-full rounded-lg border-gray-300 focus:ring-[#1B3A2D] focus:border-[#1B3A2D]">
                            </div>
                        </div>
                        <div class="pt-2 flex justify-end">
                            <button type="submit" class="px-6 py-2 bg-[#C8A84B] text-[#1B3A2D] font-bold rounded-lg hover:bg-[#B59640] transition-colors shadow-sm active:scale-95">
                                Ganti Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Profil: Aktivitas & Summary -->
        <div class="space-y-6">
            <!-- Aktivitas Login -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-50 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="p-2 bg-[#1B3A2D]/10 rounded-lg">
                            <svg class="w-5 h-5 text-[#1B3A2D]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900">Aktivitas Login</h3>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        @forelse ($activities as $log)
                        <div class="flex space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-green-500 mt-1.5"></div>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900">Login Berhasil</p>
                                <p class="text-xs text-gray-500">{{ $log->created_at->translatedFormat('d M Y, H:i') }} WIB</p>
                                <div class="mt-1 flex items-center space-x-2 text-[10px] text-gray-400">
                                    <span class="px-1.5 py-0.5 bg-gray-100 rounded">{{ $log->properties['ip'] ?? '0.0.0.0' }}</span>
                                    <span class="truncate">{{ Str::limit($log->properties['user_agent'] ?? '', 30) }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-sm text-gray-400 text-center py-4 italic">Belum ada riwayat aktivitas.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Kartu Role -->
            <div class="bg-gradient-to-br from-[#1B3A2D] to-[#2D5A45] rounded-xl p-6 shadow-md text-white">
                <div class="flex items-center space-x-4 mb-4">
                    <img src="{{ asset('logo/marinir.png') }}" alt="Marinir" class="w-10 h-10 object-contain brightness-0 invert">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-[#C8A84B] tracking-wider">Jabatan & Role</p>
                        <p class="text-sm font-semibold">{{ auth()->user()->detail->jabatan ?? '-' }}</p>
                    </div>
                </div>
                <div class="space-y-2 border-t border-white/20 pt-4">
                    <div class="flex justify-between text-xs">
                        <span class="text-white/60">Pangkat</span>
                        <span class="font-medium text-[#C8A84B]">{{ auth()->user()->detail->pangkat ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-white/60">Kompi</span>
                        <span class="font-medium">{{ auth()->user()->detail->kompi->nama ?? '-' }}</span>
                    </div>
                    @if(auth()->user()->isOperator())
                    <div class="flex justify-between text-xs">
                        <span class="text-white/60">Penugasan</span>
                        <span class="font-medium">{{ auth()->user()->kendaraanTugas->nomor_ranpur ?? 'Belum Ditugaskan' }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
