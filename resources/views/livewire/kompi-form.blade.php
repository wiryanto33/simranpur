<div>
    <div x-data="{ open: @entangle('showForm') }">
        <!-- Background Backdrop -->
        <div x-show="open" x-cloak class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 transition-opacity" aria-hidden="true"></div>

        <!-- Modal Panel -->
        <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div @click.away="open = false" class="inline-block w-full max-w-2xl overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl sm:my-8 relative">
                    
                    <div class="px-6 py-4 bg-[#1B3A2D] border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider" id="modal-title">
                            {{ $isEdit ? 'Edit Kompi' : 'Tambah Kompi Baru' }}
                        </h3>
                        <button type="button" @click="open = false" class="text-gray-300 hover:text-white focus:outline-none">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="px-6 py-6 bg-gray-50">
                        <form wire:submit.prevent="submit" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Kompi <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="nama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                    @error('nama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kode Kompi <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="kode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                    @error('kode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                    <textarea wire:model="deskripsi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50"></textarea>
                                    @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                                <button type="button" @click="open = false" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Batal
                                </button>
                                <button type="submit" class="inline-flex justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#1B3A2D] hover:bg-[#2D5A45] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1B3A2D]">
                                    Simpan Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
