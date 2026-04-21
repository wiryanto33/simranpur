<div>
    <div x-data="{ open: @entangle('showForm') }">
        <div x-show="open" x-cloak class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 transition-opacity" aria-hidden="true"></div>

        <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div @click.away="open = false" class="inline-block w-full max-w-4xl overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl sm:my-8 relative">
                    <div class="px-6 py-4 bg-[#1B3A2D] border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider" id="modal-title">
                            {{ $isEdit ? 'Edit Jadwal Pemeliharaan' : 'Tambah Jadwal Pemeliharaan' }}
                        </h3>
                        <button type="button" @click="open = false" class="text-gray-300 hover:text-white focus:outline-none">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="px-6 py-6 bg-gray-50 max-h-[80vh] overflow-y-auto">
                        <form wire:submit.prevent="submit" class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Info Dasar -->
                                <div class="space-y-4">
                                    <h4 class="font-semibold text-gray-700 border-b pb-1">Informasi Utama</h4>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Pilih Kendaraan <span class="text-red-500">*</span></label>
                                        <select wire:model="kendaraan_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                            <option value="">-- Pilih Kendaraan (Ranpur) --</option>
                                            @foreach(collect($kendaraans) as $k)
                                                @if(is_object($k))
                                                    <option value="{{ $k->id }}">{{ $k->nomor_ranpur }} - {{ $k->nama }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('kendaraan_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tanggal Mulai <span class="text-red-500">*</span></label>
                                        <input type="date" wire:model="tanggal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                        @error('tanggal') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Estimasi Pengerjaan Penuh (Hari) <span class="text-red-500">*</span></label>
                                        <input type="number" min="1" wire:model="estimasi_hari" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                        @error('estimasi_hari') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Jenis Pemeliharaan <span class="text-red-500">*</span></label>
                                        <select wire:model="jenis_pemeliharaan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                            <option value="">-- Pilih Jenis --</option>
                                            <option value="Harian">Harian</option>
                                            <option value="Mingguan">Mingguan</option>
                                            <option value="Bulanan">Bulanan</option>
                                            <option value="Triwulan">Triwulan</option>
                                            <option value="Tahunan">Tahunan</option>
                                            <option value="Insidentil">Insidentil (Perbaikan Tambahan)</option>
                                        </select>
                                        @error('jenis_pemeliharaan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Tugaskan Mekanik <span class="text-red-500">*</span></label>
                                        <div class="mt-2 grid grid-cols-2 gap-2 max-h-40 overflow-y-auto p-3 border border-gray-300 rounded-md bg-white shadow-sm">
                                            @foreach(collect($mekaniks) as $m)
                                                @if(is_object($m))
                                                    <label class="flex items-center space-x-2 text-sm cursor-pointer hover:bg-gray-50 p-1 rounded">
                                                        <input type="checkbox" wire:model="mekanik_ids" value="{{ $m->id }}" class="rounded border-gray-300 text-[#1B3A2D] focus:ring-[#1B3A2D]">
                                                        <span class="text-gray-700">{{ $m->name }}</span>
                                                    </label>
                                                @endif
                                            @endforeach
                                        </div>
                                        @error('mekanik_ids') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Status Awal</label>
                                        <select wire:model="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] bg-gray-100" @if(!$isEdit) disabled @endif>
                                            <option value="Terjadwal">Terjadwal</option>
                                            <option value="Sedang Dikerjakan">Sedang Dikerjakan</option>
                                            <option value="Selesai">Selesai</option>
                                            <option value="Ditunda">Ditunda</option>
                                            <option value="Dibatalkan">Dibatalkan</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Catatan Tambahan</label>
                                        <textarea wire:model="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50"></textarea>
                                    </div>
                                </div>

                                <!-- Checklist DINAMIS -->
                                <div class="space-y-4">
                                    <h4 class="font-semibold text-gray-700 border-b pb-1">Checklist Pekerjaan</h4>
                                    
                                    <div class="space-y-2">
                                        @foreach($checklist as $index => $item)
                                            <div class="flex items-center space-x-2">
                                                <input type="text" wire:model="checklist.{{ $index }}.task" placeholder="Cek oli, bersihkan karburator, dsj..." class="block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] text-sm">
                                                
                                                <button type="button" wire:click="removeChecklistItem({{ $index }})" class="p-2 bg-red-100 text-red-600 hover:bg-red-200 rounded-md">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                            @error('checklist.'.$index.'.task') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
                                        @endforeach
                                    </div>

                                    <button type="button" wire:click="addChecklistItem" class="mt-2 inline-flex items-center text-sm text-[#2D5A45] font-medium hover:text-[#1B3A2D]">
                                        <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Tambah Item Checklist
                                    </button>
                                </div>
                            </div>
                            
                            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                                <button type="button" @click="open = false" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Batal
                                </button>
                                <button type="submit" class="inline-flex justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#1B3A2D] hover:bg-[#2D5A45] focus:outline-none">
                                    Simpan Jadwal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
