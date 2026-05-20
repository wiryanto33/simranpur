<div>
    <div x-data="{ open: @entangle('showForm') }">
        {{-- Backdrop --}}
        <div x-show="open" x-cloak class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 transition-opacity" aria-hidden="true"></div>

        {{-- Modal --}}
        <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20">
                <div @click.away="open = false"
                     class="inline-block w-full max-w-xl overflow-hidden text-left align-middle transition-all transform bg-white rounded-xl shadow-2xl">

                    {{-- Header --}}
                    <div class="px-6 py-4 bg-[#1B3A2D] flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider">
                            {{ $isEdit ? 'Edit Data Mekanik' : 'Tambah Mekanik Baru' }}
                        </h3>
                        <button @click="open = false" class="text-gray-300 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Body --}}
                    <div class="px-6 py-6 bg-gray-50">
                        <form wire:submit.prevent="submit" class="space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                                {{-- Nama --}}
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Nama Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <input wire:model="nama" type="text" placeholder="Masukkan nama lengkap"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                    @error('nama') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Pangkat --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Pangkat</label>
                                    <input wire:model="pangkat" type="text" placeholder="cth: Sersan, Kopral"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                    @error('pangkat') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- NRP --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">NRP</label>
                                    <input wire:model="nrp" type="text" placeholder="Nomor Registrasi Prajurit"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50 font-mono">
                                    @error('nrp') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Jabatan --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                                    <input wire:model="jabatan" type="text" placeholder="cth: Teknisi Mesin"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                    @error('jabatan') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- No HP --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">No. HP</label>
                                    <input wire:model="no_hp" type="text" placeholder="0812-xxxx-xxxx"
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                    @error('no_hp') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Status --}}
                                <div class="col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Status <span class="text-red-500">*</span></label>
                                    <select wire:model="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                        <option value="Aktif">Aktif</option>
                                        <option value="Tidak Aktif">Tidak Aktif</option>
                                    </select>
                                    @error('status') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            {{-- Footer --}}
                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                                <button type="button" @click="open = false"
                                        class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-[#1B3A2D] border border-transparent rounded-md text-sm font-medium text-white hover:bg-[#2D5A45] flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $isEdit ? 'Simpan Perubahan' : 'Tambah Mekanik' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
