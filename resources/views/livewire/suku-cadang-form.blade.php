<div>
    <div x-data="{ open: @entangle('showForm') }">
        <div x-show="open" x-cloak class="fixed inset-0 z-40 bg-gray-900 bg-opacity-60" aria-hidden="true"></div>

        <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 py-6">
                <div @click.away="open = false" class="inline-block w-full max-w-2xl bg-white rounded-xl shadow-xl overflow-hidden">
                    
                    <!-- Modal Header -->
                    <div class="px-6 py-4 bg-[#1B3A2D] flex justify-between items-center">
                        <h3 class="text-base font-bold text-white uppercase tracking-wider">
                            {{ $isEdit ? 'Edit Data Suku Cadang' : 'Tambah Suku Cadang Baru' }}
                        </h3>
                        <button @click="open = false" class="text-gray-300 hover:text-white">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-6 max-h-[80vh] overflow-y-auto bg-gray-50">
                        <form wire:submit.prevent="submit" class="space-y-5">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- Kode -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Kode Suku Cadang <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="kode" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50 font-mono" placeholder="Cth: SC-ENY-001">
                                    @error('kode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Nama -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Suku Cadang <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="nama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50" placeholder="Cth: Filter Oli Mesin">
                                    @error('nama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Satuan -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Satuan <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="satuan" list="satuan_list" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50" placeholder="Pilih atau ketik satuan">
                                    <datalist id="satuan_list">
                                        @foreach($satuanList as $s)
                                            <option value="{{ $s }}">
                                        @endforeach
                                    </datalist>
                                    @error('satuan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Lokasi -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Lokasi Penyimpanan</label>
                                    <input type="text" wire:model="lokasi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50" placeholder="Cth: Gudang A - Rak 3">
                                    @error('lokasi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Stok -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Stok Saat Ini <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model="stok" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                    @error('stok') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Stok Minimum -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Stok Minimum (Alert) <span class="text-red-500">*</span></label>
                                    <input type="number" wire:model="stok_minimum" min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                    <p class="text-xs text-gray-400 mt-1">Sistem akan memperingatkan jika stok ≤ nilai ini.</p>
                                    @error('stok_minimum') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Foto Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Foto Suku Cadang</label>
                                
                                <!-- Preview existing / temporary -->
                                @if($foto)
                                    <div class="mt-2 mb-3 relative inline-block">
                                        <img src="{{ $foto->temporaryUrl() }}" class="h-32 w-32 object-cover rounded-lg border-2 border-[#2D5A45] shadow-sm">
                                        <span class="absolute -top-2 -right-2 bg-green-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">Baru</span>
                                    </div>
                                @elseif($fotoPreview)
                                    <div class="mt-2 mb-3 relative inline-block">
                                        <img src="{{ $fotoPreview }}" class="h-32 w-32 object-cover rounded-lg border-2 border-gray-300 shadow-sm">
                                        <span class="absolute -top-2 -right-2 bg-gray-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full">Ada</span>
                                    </div>
                                @endif

                                <div class="mt-2 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed {{ $foto ? 'border-[#2D5A45]' : 'border-gray-300' }} rounded-lg bg-white hover:bg-gray-50 transition-colors"
                                     x-data="{ isUploading: false, progress: 0 }"
                                     x-on:livewire-upload-start="isUploading = true"
                                     x-on:livewire-upload-finish="isUploading = false"
                                     x-on:livewire-upload-progress="progress = $event.detail.progress">
                                    <div class="space-y-1 text-center w-full" x-show="!isUploading">
                                        <svg class="mx-auto h-10 w-10 text-gray-300" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <label class="cursor-pointer text-sm text-[#2D5A45] font-medium hover:underline">
                                            {{ $fotoPreview && !$foto ? 'Ganti foto' : 'Klik untuk unggah foto' }}
                                            <input type="file" wire:model="foto" accept="image/*" class="sr-only">
                                        </label>
                                        <p class="text-xs text-gray-400">PNG, JPG — Maks. 2MB</p>
                                    </div>
                                    <div x-show="isUploading" class="w-full">
                                        <p class="text-sm text-gray-600 mb-2 text-center">Mengunggah... <span x-text="progress"></span>%</p>
                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                            <div class="bg-[#2D5A45] h-2 rounded-full" :style="`width: ${progress}%`"></div>
                                        </div>
                                    </div>
                                </div>
                                @error('foto') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                                <button type="button" @click="open = false" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Batal
                                </button>
                                <button type="submit" class="px-5 py-2 bg-[#1B3A2D] rounded-md text-sm font-semibold text-white hover:bg-[#2D5A45] flex items-center gap-2 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ $isEdit ? 'Perbarui Data' : 'Simpan Data' }}
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
