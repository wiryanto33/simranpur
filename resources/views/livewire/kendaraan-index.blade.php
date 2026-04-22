<div>
    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            <span class="font-medium">Berhasil!</span> {{ session('message') }}
        </div>
    @endif

    <div class="mb-4 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama / nomor ranpur..." class="rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50 text-sm">
            
            <select wire:model.live="filterStatus" class="rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50 text-sm">
                <option value="">Semua Status</option>
                <option value="Siap Tempur">Siap Tempur</option>
                <option value="Standby">Standby</option>
                <option value="Perbaikan">Perbaikan</option>
                <option value="Tidak Layak">Tidak Layak</option>
            </select>

            <select wire:model.live="filterKompi" class="rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50 text-sm">
                <option value="">Semua Kompi</option>
                @foreach(collect($kompiList) as $kompi)
                    @if(is_object($kompi))
                        <option value="{{ $kompi->id }}">{{ $kompi->nama }}</option>
                    @endif
                @endforeach
            </select>
        </div>

        @can('create_kendaraan')
        <button wire:click="$dispatchTo('kendaraan-form', 'createKendaraan')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#1B3A2D] hover:bg-[#2D5A45]">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Tambah Kendaraan
        </button>
        @endcan
    </div>

    <!-- Include Form Component -->
    @livewire('kendaraan-form')

    <!-- Delete Confirmation Modal -->
    @if($confirmingDeletion)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="px-4 pt-5 pb-4 bg-white sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Hapus Kendaraan Tempur</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Apakah Anda yakin ingin menghapus data kendaraan tempur ini? Data tidak akan benar-benar terhapus (soft delete) namun akan hilang dari daftar aktif.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="deleteKendaraan" type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Hapus</button>
                    <button wire:click="$set('confirmingDeletion', false)" type="button" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <x-card>
        <x-table>
            <x-slot name="head">
                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Ranpur</th>
                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Jenis</th>
                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Kompi</th>
                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Status</th>
                <th scope="col" class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase">Aksi</th>
            </x-slot>
            <x-slot name="body">
                @forelse($kendaraanList as $k)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 w-10 h-10">
                                    @if($k->foto)
                                        <img class="w-10 h-10 rounded-full object-cover" src="{{ asset('storage/'.$k->foto) }}" alt="">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $k->nama }}</div>
                                    <div class="text-sm text-gray-500">{{ $k->nomor_ranpur }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $k->jenis }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $k->kompi->nama ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <x-badge :color="$k->status_badge">{{ $k->status }}</x-badge>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            @can('view_kendaraan')
                            <a href="{{ route('kendaraan.show', $k->id) }}" class="text-blue-600 hover:text-blue-900 mx-1 border border-blue-200 rounded p-1">Detail</a>
                            @endcan
                            @can('edit_kendaraan')
                            <button wire:click="$dispatchTo('kendaraan-form', 'editKendaraan', { id: {{ $k->id }} })" class="text-indigo-600 hover:text-indigo-900 mx-1 border border-indigo-200 rounded p-1">Edit</button>
                            @endcan
                            @can('delete_kendaraan')
                            <button wire:click="confirmDelete({{ $k->id }})" class="text-red-600 hover:text-red-900 mx-1 border border-red-200 rounded p-1">Hapus</button>
                            @endcan
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            Data kendaraan tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </x-slot>
            <x-slot name="pagination">
                {{ $kendaraanList->links() }}
            </x-slot>
        </x-table>
    </x-card>
</div>
