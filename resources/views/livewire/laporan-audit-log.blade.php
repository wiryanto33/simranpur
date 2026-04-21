<div>
    <x-page-header title="Log Audit Aktivitas" subtitle="Aktivitas sistem dan riwayat perubahan data">
    </x-page-header>

    <x-card class="mt-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div>
                <x-input-label for="user" value="User" />
                <select wire:model.live="search_user" id="user" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-[#1B3A2D] focus:ring-[#1B3A2D]">
                    <option value="">Semua User</option>
                    @foreach(collect($users) as $u)
                        @if(is_object($u))
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div>
                <x-input-label for="modul" value="Modul / Subjek" />
                <x-text-input wire:model.live="search_modul" id="modul" placeholder="Cari modul..." class="mt-1 block w-full" />
            </div>
            <div>
                <x-input-label for="start" value="Mulai" />
                <x-text-input wire:model.live="start_date" id="start" type="date" class="mt-1 block w-full" />
            </div>
            <div>
                <x-input-label for="end" value="Selesai" />
                <x-text-input wire:model.live="end_date" id="end" type="date" class="mt-1 block w-full" />
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modul</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Detail</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($logs as $log)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">{{ $log->causer->name ?? 'System' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase 
                                {{ $log->description == 'created' ? 'bg-green-100 text-green-700' : 
                                   ($log->description == 'updated' ? 'bg-blue-100 text-blue-700' : 'bg-red-100 text-red-700') }}">
                                {{ $log->description }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-600 font-mono">
                            {{ class_basename($log->subject_type) }}
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500">
                            @if($log->properties->count() > 0)
                                <div class="max-w-xs overflow-hidden text-ellipsis italic">
                                    {{ json_encode($log->properties['attributes'] ?? $log->properties) }}
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </x-card>
</div>
