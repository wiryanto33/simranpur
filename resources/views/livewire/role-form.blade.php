<div>
    <div x-data="{ open: @entangle('showForm') }">
        <div x-show="open" x-cloak class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 transition-opacity" aria-hidden="true"></div>

        <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div @click.away="open = false" class="inline-block w-full max-w-4xl overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl sm:my-8 relative">
                    <div class="px-6 py-4 bg-[#1B3A2D] border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider" id="modal-title">
                            {{ $isEdit ? 'Ubah Hak Akses Role' : 'Buat Role Baru' }}
                        </h3>
                        <button type="button" @click="open = false" class="text-gray-300 hover:text-white focus:outline-none">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="px-6 py-6 bg-gray-50 max-h-[80vh] overflow-y-auto">
                        <form wire:submit.prevent="submit" class="space-y-6">
                            
                            <!-- Role Name -->
                            <div class="bg-white p-4 rounded-md shadow-sm border border-gray-200">
                                <label class="block text-sm font-medium text-gray-700">Nama Role <span class="text-red-500">*</span></label>
                                <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50" placeholder="Cth: Reviewer Laporan">
                                <p class="text-xs text-gray-500 mt-1">Nama role akan menjadi pengidentifikasi utama akses grup pengguna.</p>
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            
                            <!-- Permissions Selection -->
                            <div>
                                <div class="flex justify-between items-end border-b pb-2 mb-4">
                                    <h4 class="font-bold text-gray-800 uppercase tracking-wide text-sm">Hak Akses Modul (Permissions)</h4>
                                    <div class="flex space-x-3 text-xs">
                                        <button type="button" wire:click="selectAll" class="text-[#2D5A45] font-semibold hover:underline border border-[#2D5A45] rounded px-2 py-1 bg-green-50">Tandai Semua (Select All)</button>
                                        <button type="button" wire:click="deselectAll" class="text-red-600 font-semibold hover:underline border border-red-200 rounded px-2 py-1 bg-red-50">Hapus Semua (Clear)</button>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                    @forelse($permissionsGrouped as $groupName => $perms)
                                        <div class="bg-white rounded-md border border-gray-200 shadow-sm overflow-hidden">
                                            <div class="bg-gray-100 px-4 py-2 border-b border-gray-200">
                                                <h5 class="text-xs font-bold text-gray-700 uppercase">{{ $groupName }}</h5>
                                            </div>
                                            <div class="p-4 space-y-3">
                                                @foreach($perms as $perm)
                                                    <label class="flex items-start cursor-pointer group">
                                                        <div class="flex items-center h-5">
                                                            <input type="checkbox" wire:model="selectedPermissions" value="{{ $perm['name'] }}" class="w-4 h-4 text-[#2D5A45] border-gray-300 rounded focus:ring-[#2D5A45] cursor-pointer">
                                                        </div>
                                                        <div class="ml-3 text-sm">
                                                            <span class="font-medium text-gray-700 group-hover:text-gray-900">{{ $perm['action_label'] }}</span>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-span-full border border-dashed border-gray-300 rounded p-6 text-center">
                                            <p class="text-gray-500 text-sm">Sistem belum memiliki referensi permission dasar di database. Harap jalankan migrasi permissions awal/seeder.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 mt-8 pt-4 border-t border-gray-200">
                                <button type="button" @click="open = false" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Batal
                                </button>
                                <button type="submit" class="inline-flex justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#1B3A2D] hover:bg-[#2D5A45] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1B3A2D]">
                                    Simpan Konfigurasi Role
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
