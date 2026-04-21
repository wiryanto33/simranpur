<div>
    @if($showModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-75">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800">Ajukan Permintaan Suku Cadang</h3>
                <button wire:click="$set('showModal', false)" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form wire:submit.prevent="submit" class="p-6 space-y-4">
                <div class="bg-gray-50 p-4 rounded-md border border-gray-100">
                    <p class="text-xs font-bold text-gray-400 uppercase mb-2">Data Kerusakan</p>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Unit Ranpur:</span>
                            <span class="block font-semibold text-gray-800">{{ $laporan->kendaraan->nama ?? '-' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Prioritas:</span>
                            <span class="block font-semibold text-gray-800">{{ $laporan->tingkat_prioritas }}</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Suku Cadang yang Dibutuhkan</label>
                    <div class="space-y-3">
                        @foreach($items as $index => $item)
                        <div class="flex gap-4 items-start">
                            <div class="flex-1">
                                <select wire:model="items.{{ $index }}.suku_cadang_id" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-[#1B3A2D] focus:ring-[#1B3A2D]">
                                    <option value="">-- Pilih Suku Cadang --</option>
                                    @foreach(collect($sukuCadangs) as $sc)
                                        @if(is_object($sc))
                                            <option value="{{ $sc->id }}">{{ $sc->nama }} (Stok: {{ $sc->stok }} {{ $sc->satuan }})</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error("items.{$index}.suku_cadang_id") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="w-24">
                                <input type="number" wire:model="items.{{ $index }}.jumlah" class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-[#1B3A2D] focus:ring-[#1B3A2D]" placeholder="Jml">
                                @error("items.{$index}.jumlah") <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <button type="button" wire:click="removeItem({{ $index }})" class="p-2 text-red-500 hover:bg-red-50 rounded-md">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                        @endforeach
                        
                        <button type="button" wire:click="addItem" class="text-sm text-blue-600 font-semibold hover:underline flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Tambah Baris
                        </button>
                        @error('items') <p class="text-red-500 text-xs font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Keterangan Tambahan (Opsional)</label>
                    <textarea wire:model="keterangan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1B3A2D] focus:ring-[#1B3A2D] text-sm" placeholder="Tuliskan alasan atau catatan permintaan..."></textarea>
                </div>

                <div class="pt-4 border-t flex justify-end gap-3">
                    <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-[#1B3A2D] border border-transparent rounded-md text-sm font-medium text-[#C8A84B] hover:bg-opacity-90 shadow-sm">Kirim Permintaan</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
