<div>
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50">
            <span class="font-medium">Berhasil:</span> {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50">
            <span class="font-medium">Error:</span> {{ session('error') }}
        </div>
    @endif

    <div class="mb-4 bg-white p-4 rounded-lg shadow-sm border border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="flex gap-4 w-full md:w-1/2">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari role (peran)..." class="w-full text-sm rounded-md border-gray-300 shadow-sm focus:border-[#2D5A45] focus:ring focus:ring-[#2D5A45] focus:ring-opacity-50">
        </div>
        
        <div>
            @hasanyrole('Admin')
            <button wire:click="$dispatchTo('role-form', 'createRole')" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#1B3A2D] hover:bg-[#2D5A45]">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Buat Role Baru
            </button>
            @endhasanyrole
        </div>
    </div>

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
                            <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">Hapus Role</h3>
                            <div class="mt-2 text-sm text-gray-500">Apakah Anda yakin ingin menghapus Role ini? Pengguna yang terikat dengan Role ini akan kehilangan hak akses dari daftar Permission di dalamnya.</div>
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 bg-gray-50 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="deleteRole" type="button" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 sm:ml-3 sm:w-auto sm:text-sm">Hapus Role</button>
                    <button wire:click="$set('confirmingDeletion', false)" type="button" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <x-card>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hak Akses (Permissions)</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($roles as $role)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900 border border-[#2D5A45] rounded px-3 py-1 bg-green-50 inline-block">{{ $role->name }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @php
                                        $perms = $role->permissions;
                                        $count = count($perms);
                                    @endphp
                                    
                                    @if($count == 0)
                                        <span class="text-xs text-gray-400 italic">Belum ada izin akses.</span>
                                    @else
                                        @foreach($perms->take(5) as $perm)
                                            <span class="px-2 py-1 inline-flex text-[10px] leading-4 font-medium rounded bg-gray-100 text-gray-600 border border-gray-200">
                                                {{ $perm->name }}
                                            </span>
                                        @endforeach
                                        
                                        @if($count > 5)
                                            <span class="px-2 py-1 inline-flex text-[10px] leading-4 font-medium rounded bg-indigo-50 text-indigo-700 border border-indigo-200 font-bold">
                                                + {{ $count - 5 }} lainnya
                                            </span>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                @hasanyrole('Admin')
                                <button wire:click="$dispatchTo('role-form', 'editRole', { id: {{ $role->id }} })" class="text-blue-600 hover:text-blue-900 mr-3">Edit Role (Kelola Izin)</button>
                                
                                @if(!in_array($role->name, ['Admin', 'Super Admin']))
                                    <button wire:click="confirmDelete({{ $role->id }})" class="text-red-600 hover:text-red-900">Hapus</button>
                                @endif
                                @endhasanyrole
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                Tidak ada data role.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $roles->links() }}
        </div>
    </x-card>
</div>
