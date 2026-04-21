<div>
    <div x-data="{ open: @entangle('showForm') }">
        <div x-show="open" x-cloak class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 transition-opacity" aria-hidden="true"></div>

        <div x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                
                <div @click.away="open = false" class="inline-block w-full max-w-2xl overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl sm:my-8 relative">
                    <div class="px-6 py-4 bg-[#1B3A2D] border-b border-gray-200 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white uppercase tracking-wider" id="modal-title">
                            {{ $isEdit ? 'Ubah Data Pengguna' : 'Tambah Pengguna Baru' }}
                        </h3>
                        <button type="button" @click="open = false" class="text-gray-300 hover:text-white focus:outline-none">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="px-6 py-6 bg-gray-50 max-h-[80vh] overflow-y-auto">
                        <form wire:submit.prevent="submit" class="space-y-4">
                            
                            <h4 class="font-semibold text-gray-700 border-b pb-1">Akun Login</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email <span class="text-red-500">*</span></label>
                                    <input type="email" wire:model="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Password @if(!$isEdit)<span class="text-red-500">*</span>@endif</label>
                                    <input type="password" wire:model="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50" placeholder="{{ $isEdit ? 'Isi untuk mereset password' : 'Min. 8 karakter' }}">
                                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Role Sistem <span class="text-red-500">*</span></label>
                                    <select wire:model="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                        <option value="">-- Pilih Role --</option>
                                        @foreach(collect($roles) as $r)
                                            @if(is_object($r))
                                                <option value="{{ $r->name }}">{{ $r->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('role') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            <h4 class="font-semibold text-gray-700 border-b pb-1 mt-6">Detail Penugasan (Tidak Wajib)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Pangkat</label>
                                    <input type="text" wire:model="pangkat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50" placeholder="Cth: Sersan Mayor">
                                    @error('pangkat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Jabatan</label>
                                    <input type="text" wire:model="jabatan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                    @error('jabatan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div x-data="{ role: @entangle('role') }" class="col-span-1 md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div :class="role === 'Operator' ? 'col-span-1' : 'col-span-1 md:col-span-2'">
                                        <label class="block text-sm font-medium text-gray-700">Afiliasi Kompi</label>
                                        <select wire:model="kompi_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
                                            <option value="">-- Tanpa Ikatan Kompi --</option>
                                            @foreach(collect($kompis) as $k)
                                                @if(is_object($k))
                                                    <option value="{{ $k->id }}">{{ $k->kode }} - {{ $k->nama }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        @error('kompi_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>

                                    <div x-show="role === 'Operator'" x-cloak class="col-span-1 border-l-4 border-orange-500 pl-3">
                                        <label class="block text-sm font-medium text-gray-700 text-orange-700">Penugasan Kendaraan (Ranpur)</label>
                                        <select wire:model="kendaraan_id" class="mt-1 block w-full rounded-md border-orange-300 text-orange-900 shadow-sm focus:border-orange-500 focus:ring focus:ring-orange-500 focus:ring-opacity-50 bg-orange-50">
                                            <option value="">-- Belum Ditugaskan --</option>
                                            @foreach(collect($kendaraans) as $k)
                                                @if(is_object($k))
                                                    <option value="{{ $k->id }}">{{ $k->nomor_ranpur }} - {{ $k->nama }} ({{ $k->status }})</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <p class="text-[10px] text-orange-600 mt-1">* Wajib untuk Operator agar dapat membuat laporan kerusakan.</p>
                                        @error('kendaraan_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200">
                                <button type="button" @click="open = false" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Batal
                                </button>
                                <button type="submit" class="inline-flex justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#1B3A2D] hover:bg-[#2D5A45] focus:outline-none">
                                    Simpan Pengguna
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
