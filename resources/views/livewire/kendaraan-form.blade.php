<div>
    <div x-data="{ open: @entangle('showForm') }">
        <!-- Background Backdrop -->
        <div x-show="open" x-cloak class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 transition-opacity" aria-hidden="true"></div>

        <!-- Modal Panel -->
        <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div @click.away="open = false" class="inline-block w-full max-w-4xl overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl sm:my-8 relative">
                    
                    <div class="px-6 py-4 bg-[#1B3A2D] border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider" id="modal-title">
                            {{ $isEdit ? 'Edit Kendaraan Tempur' : 'Tambah Kendaraan Baru' }}
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
                                    <label class="block text-sm font-medium text-gray-700">Nomor Ranpur <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="nomor_ranpur" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                    @error('nomor_ranpur') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Kendaraan <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="nama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                    @error('nama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jenis Kendaraan <span class="text-red-500">*</span></label>
                                    <select wire:model="jenis" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                        <option value="">-- Pilih Jenis --</option>
                                        <option value="Tank Tempur Utama">Tank Tempur Utama (MBT)</option>
                                        <option value="Tank Ringan">Tank Ringan</option>
                                        <option value="Kendaraan Pendarat Amfibi">Kendaraan Pendarat Amfibi (LVT)</option>
                                        <option value="Kendaraan Pengangkut Pasukan">Pengangkut Pasukan (APC)</option>
                                    </select>
                                    @error('jenis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tahun Pembuatan <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model="tahun" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                    @error('tahun') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kompi <span class="text-red-500">*</span></label>
                                    <select wire:model="kompi_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                        <option value="">-- Pilih Kompi --</option>
                                        @foreach(collect($kompiList) as $kompi)
                                            @if(is_object($kompi))
                                                <option value="{{ $kompi->id }}">{{ $kompi->nama }} ({{ $kompi->kode }})</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('kompi_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                                    <select wire:model="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                        <option value="Siap Tempur">Siap Tempur</option>
                                        <option value="Standby">Standby</option>
                                        <option value="Perbaikan">Perbaikan</option>
                                        <option value="Tidak Layak">Tidak Layak</option>
                                    </select>
                                    @error('status') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Foto Ranpur</label>
                                    <input type="file" wire:model="foto" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#1B3A2D]/10 file:text-[#1B3A2D] hover:file:bg-[#1B3A2D]/20">
                                    <div wire:loading wire:target="foto" class="text-sm text-blue-600 mt-1 font-semibold">Mengunggah foto... mohon tunggu.</div>
                                    @error('foto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    
                                    @if ($foto)
                                        <div class="mt-2 text-sm text-gray-600">Preview: <br> <img src="{{ $foto->temporaryUrl() }}" class="h-24 w-auto rounded border"></div>
                                    @elseif ($fotoPrev)
                                        <div class="mt-2 text-sm text-gray-600">Foto Saat Ini: <br> <img src="{{ asset('storage/'.$fotoPrev) }}" class="h-24 w-auto rounded border"></div>
                                    @endif
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
