<div>
    <div x-data="{ open: @entangle('showForm') }">
        <div x-show="open" x-cloak class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 transition-opacity" aria-hidden="true"></div>

        <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
                <div @click.away="open = false" class="inline-block w-full max-w-2xl overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl">
                    <div class="px-6 py-4 bg-[#1B3A2D] flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider">
                            {{ $isEdit ? 'Edit Laporan Kerusakan' : 'Buat Laporan Kerusakan' }}
                        </h3>
                        <button @click="open = false" class="text-gray-300 hover:text-white">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <div class="px-6 py-6 bg-gray-50 max-h-[80vh] overflow-y-auto">
                        <form wire:submit.prevent="submit" class="space-y-6">

                            @if(!$isEdit)
                            <div class="bg-blue-50 p-4 rounded-md border border-blue-200">
                                <p class="text-sm text-blue-800"><span class="font-bold">Info:</span> Menyubmit laporan ini akan otomatis mengubah status kendaraan menjadi <b>"Perbaikan"</b>.</p>
                            </div>
                            @endif

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Kendaraan Tempur <span class="text-red-500">*</span></label>
                                    <select wire:model="kendaraan_id" {{ ($isEdit || $isOperator) ? 'disabled' : '' }} class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50 {{ ($isEdit || $isOperator) ? 'bg-gray-100 cursor-not-allowed' : '' }}">
                                        <option value="">-- Pilih Kendaraan --</option>
                                        @foreach($kendaraans as $k)
                                            @if(is_object($k))
                                                <option value="{{ $k->id }}" {{ $kendaraan_id == $k->id ? 'selected' : '' }}>{{ $k->nama }} ({{ $k->nomor_ranpur ?? $k->no_register }})</option>
                                            @endif
                                        @endforeach
                                        {{-- Jika edit, tampilkan kendaraan yang sedang terpilih meski berstatus Perbaikan --}}
                                        @if($isEdit && !collect($kendaraans)->contains('id', $kendaraan_id))
                                            @php $currentKend = \App\Models\Kendaraan::find($kendaraan_id); @endphp
                                            @if($currentKend)
                                                <option value="{{ $currentKend->id }}" selected>{{ $currentKend->nama }} ({{ $currentKend->nomor_ranpur ?? $currentKend->no_register }}) — {{ $currentKend->status }}</option>
                                            @endif
                                        @endif
                                    </select>
                                    @if($isOperator && collect($kendaraans)->count() == 0)
                                        <p class="text-xs text-red-500 mt-1 font-semibold">Anda belum ditugaskan pada kendaraan manapun. Hubungi Admin.</p>
                                    @elseif(!$isEdit && !$isOperator && collect($kendaraans)->count() == 0)
                                        <p class="text-xs text-orange-500 mt-1">Tidak ada kendaraan dengan status 'Siap Tempur'.</p>
                                    @endif
                                    @error('kendaraan_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tingkat Prioritas <span class="text-red-500">*</span></label>
                                    <select wire:model="tingkat_prioritas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                        <option value="Rendah">Rendah</option>
                                        <option value="Sedang">Sedang</option>
                                        <option value="Tinggi">Tinggi</option>
                                    </select>
                                    @error('tingkat_prioritas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Deskripsi Kerusakan <span class="text-red-500">*</span></label>
                                    <textarea wire:model="deskripsi" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50" placeholder="Jelaskan detail kerusakan..."></textarea>
                                    @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Foto Existing (saat edit) -->
                                @if($isEdit && count($fotosExisting) > 0)
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Saat Ini <span class="text-gray-400 text-xs">(klik × untuk hapus)</span></label>
                                    <div class="grid grid-cols-5 gap-2">
                                        @foreach($fotosExisting as $i => $path)
                                            <div class="relative group">
                                                <img src="{{ Storage::url($path) }}" class="h-20 w-full object-cover rounded border border-gray-200">
                                                <button type="button" wire:click="removeFotoExisting({{ $i }})"
                                                    class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center shadow hover:bg-red-700 focus:outline-none">×</button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                <!-- Upload Foto Baru -->
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">{{ $isEdit ? 'Tambah Foto Baru' : 'Dokumentasi Foto' }} <span class="text-gray-400 text-xs">(opsional, maks. 5 foto)</span></label>
                                    <div class="mt-1 border-2 border-dashed border-gray-300 rounded-md p-4 text-center bg-white"
                                         x-data="{ isUploading: false, progress: 0 }"
                                         x-on:livewire-upload-start="isUploading = true"
                                         x-on:livewire-upload-finish="isUploading = false"
                                         x-on:livewire-upload-progress="progress = $event.detail.progress">
                                        <div x-show="!isUploading">
                                            <label class="cursor-pointer text-sm text-[#2D5A45] font-medium hover:underline">
                                                Klik untuk unggah foto
                                                <input type="file" wire:model="fotos" multiple accept="image/*" class="sr-only">
                                            </label>
                                            <p class="text-xs text-gray-400 mt-1">PNG, JPG — maks. 2MB</p>
                                        </div>
                                        <div x-show="isUploading" class="w-full">
                                            <p class="text-xs text-gray-600 mb-2">Mengunggah... <span x-text="progress"></span>%</p>
                                            <div class="w-full bg-gray-200 rounded-full h-2"><div class="bg-[#2D5A45] h-2 rounded-full" :style="`width: ${progress}%`"></div></div>
                                        </div>
                                    </div>
                                    @if($fotos)
                                    <div class="mt-3 grid grid-cols-5 gap-2">
                                        @foreach($fotos as $foto)
                                            <img src="{{ $foto->temporaryUrl() }}" class="h-20 w-full object-cover rounded border border-gray-200">
                                        @endforeach
                                    </div>
                                    @endif
                                    @error('fotos') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    @error('fotos.*') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                                <button type="button" @click="open = false" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-[#1B3A2D] border border-transparent rounded-md text-sm font-medium text-white hover:bg-[#2D5A45] flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    {{ $isEdit ? 'Simpan Perubahan' : 'Kirim Laporan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
