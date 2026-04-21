<div>
    <div x-data="{ open: @entangle('showForm') }">
        <div x-show="open" x-cloak class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 transition-opacity"></div>

        <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
                <div @click.away="open = false" class="inline-block w-full max-w-3xl overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl">
                    <div class="px-6 py-4 bg-[#1B3A2D] flex justify-between items-center text-white">
                        <h3 class="text-lg font-bold uppercase">{{ $isEdit ? 'Edit Laporan Perbaikan' : 'Buat Laporan Perbaikan' }}</h3>
                        <button @click="open = false" class="text-gray-300 hover:text-white">&times;</button>
                    </div>

                    <div class="px-6 py-6 bg-gray-50 max-h-[85vh] overflow-y-auto">
                        @if($dataKerusakan)
                            <div class="mb-6 p-4 bg-white rounded border border-gray-200">
                                <h4 class="text-sm font-bold text-gray-500 uppercase mb-2">Data Kerusakan</h4>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <div class="text-gray-500">Unit Ranpur:</div>
                                        <div class="font-bold font-mono">{{ $dataKerusakan->kendaraan->nama }} ({{ $dataKerusakan->kendaraan->no_register }})</div>
                                    </div>
                                    <div>
                                        <div class="text-gray-500">Deskripsi Kerusakan:</div>
                                        <div class="italic">"{{ $dataKerusakan->deskripsi }}"</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <form wire:submit.prevent="submit" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Mulai <span class="text-red-500">*</span></label>
                                    <input type="date" wire:model="tanggal_mulai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                    @error('tanggal_mulai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Tanggal Selesai</label>
                                    <input type="date" wire:model="tanggal_selesai" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                    @error('tanggal_selesai') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Uraian Pekerjaan <span class="text-red-500">*</span></label>
                                    <textarea wire:model="deskripsi" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50" placeholder="Jelaskan tindakan perbaikan yang dilakukan..."></textarea>
                                    @error('deskripsi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <!-- Spare Parts Section -->
                                <div class="col-span-2 border-t pt-4">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="text-sm font-bold text-gray-700 uppercase">Suku Cadang yang Telah Disetujui</h4>
                                    </div>

                                    @if(count($sukuCadangList) > 0)
                                        <div class="bg-gray-50 rounded-lg overflow-hidden border border-gray-200">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-100">
                                                    <tr>
                                                        <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-500 uppercase">Nama Suku Cadang</th>
                                                        <th class="px-4 py-2 text-center text-[10px] font-bold text-gray-500 uppercase">Jumlah</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-100 italic">
                                                    @foreach($sukuCadangList as $item)
                                                        <tr>
                                                            <td class="px-4 py-2 text-sm text-gray-700 font-medium">{{ $item['nama'] }}</td>
                                                            <td class="px-4 py-2 text-sm text-gray-900 font-bold text-center">{{ $item['jumlah'] }} {{ $item['satuan'] }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-center text-xs text-gray-400 py-4 border-2 border-dashed rounded bg-gray-50">
                                            @if($isEdit)
                                                Tidak ada pemakaian suku cadang pada laporan ini.
                                            @else
                                                <span class="text-red-500 font-bold">⚠️ Perhatian: tidak ditemukan permintaan sparepart yang disetujui untuk laporan ini.</span>
                                            @endif
                                        </p>
                                    @endif
                                    <p class="mt-2 text-[10px] text-gray-400">* Daftar suku cadang di atas diambil dari permintaan yang telah disetujui oleh Logistik.</p>
                                </div>

                                <!-- Photo Hasil Section -->
                                <div class="col-span-2 border-t pt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Foto Hasil Perbaikan</label>
                                    
                                    <!-- Existing Photos -->
                                    @if(count($fotos_existing) > 0)
                                        <div class="grid grid-cols-5 gap-2 mb-4">
                                            @foreach($fotos_existing as $index => $path)
                                                <div class="relative group">
                                                    <img src="{{ Storage::url($path) }}" class="h-20 w-full object-cover rounded border">
                                                    <button type="button" wire:click="removeFotoExisting({{ $index }})" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs shadow hover:bg-red-700">&times;</button>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md bg-white">
                                        <div class="space-y-1 text-center">
                                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 00-4 4H12a4 4 0 00-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 005.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                            <div class="flex text-sm text-gray-600">
                                                <label class="relative cursor-pointer bg-white rounded-md font-medium text-[#2D5A45] hover:text-[#1B3A2D] focus-within:outline-none">
                                                    <span>{{ $isEdit ? 'Tambah foto baru' : 'Unggah foto hasil' }}</span>
                                                    <input wire:model="fotos_hasil" type="file" class="sr-only" multiple accept="image/*">
                                                </label>
                                            </div>
                                            <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                        </div>
                                    </div>
                                    @if ($fotos_hasil)
                                        <div class="mt-3 grid grid-cols-5 gap-2">
                                            @foreach ($fotos_hasil as $foto)
                                                <img src="{{ $foto->temporaryUrl() }}" class="h-20 w-full object-cover rounded border">
                                            @endforeach
                                        </div>
                                    @endif
                                    @error('fotos_hasil') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                                <button type="button" @click="open = false" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                                <button type="submit" class="px-4 py-2 bg-[#1B3A2D] text-white rounded-md text-sm font-medium hover:bg-[#2D5A45] flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                    {{ $isEdit ? 'Simpan Perubahan' : 'Simpan Laporan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
