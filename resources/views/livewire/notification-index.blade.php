<div>
    <div class="mb-6 md:flex md:items-center md:justify-between">
        <div class="flex-1 min-w-0">
            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                Pusat Notifikasi
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                Lihat semua pembaruan dan pemberitahuan aktivitas Anda di SIMRANPUR.
            </p>
        </div>
        <div class="flex mt-4 md:mt-0 md:ml-4 gap-3 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button wire:click="setFilter('all')" class="{{ $filter === 'all' ? 'border-[#C8A84B] text-[#1B3A2D] font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 text-sm transition-colors">
                    Semua
                </button>
                <button wire:click="setFilter('unread')" class="{{ $filter === 'unread' ? 'border-[#C8A84B] text-[#1B3A2D] font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 text-sm transition-colors">
                    Belum Dibaca
                </button>
                @if(auth()->user()->hasRole('Admin'))
                <button wire:click="setFilter('global')" class="{{ $filter === 'global' ? 'border-[#C8A84B] text-[#1B3A2D] font-bold' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 text-sm transition-colors">
                    Log Sistem (Global)
                </button>
                @endif
            </nav>
        </div>
        <div class="mt-4 md:mt-0 md:ml-4 flex gap-2 self-end pb-2">
            @if(auth()->user()->unreadNotifications->count() > 0)
            <button wire:click="markAllAsRead" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-[#1B3A2D] border border-transparent rounded-md shadow-sm hover:bg-[#2D5A45] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#C8A84B] transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                Tandai semua dibaca
            </button>
            @endif
        </div>
    </div>

    <!-- Tabel Notifikasi -->
    <div class="flex flex-col bg-white border border-gray-200 rounded-lg shadow-sm">
        <ul role="list" class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
                <li class="relative py-5 px-4 sm:px-6 hover:bg-gray-50 transition-colors {{ empty($notification->read_at) ? 'bg-[#1B3A2D]/5' : '' }}">
                    <div class="flex justify-between items-start">
                        <div class="flex items-start flex-1" wire:click="markAsRead('{{ $notification->id }}')" style="cursor: pointer;">
                            <div class="flex-shrink-0 mt-1">
                                @php $type = $notification->data['type'] ?? 'info'; @endphp
                                @if($type === 'success')
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                @elseif($type === 'warning' || $type === 'danger')
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100">
                                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    </div>
                                @else
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 min-w-0 flex-1">
                                <h3 class="text-sm font-medium text-gray-900 {{ empty($notification->read_at) ? 'font-bold' : '' }}">
                                    {{ $notification->data['title'] ?? 'Pemberitahuan Sistem' }}
                                    @if($filter === 'global')
                                        <span class="text-xs font-normal text-gray-400 ml-2">Untuk: {{ $notification->notifiable->name ?? 'Unknown' }}</span>
                                    @endif
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="ml-4 flex-shrink-0 flex flex-col items-end whitespace-nowrap">
                            <p class="text-sm text-gray-500 mb-2">
                                <time datetime="{{ $notification->created_at->toIso8601String() }}">
                                    {{ $notification->created_at->isoFormat('D MMM Y HH:mm') }}
                                </time>
                            </p>
                            <div class="flex gap-2 items-center">
                                @if(empty($notification->read_at))
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-[#C8A84B]/20 text-[#1B3A2D]">
                                        Baru
                                    </span>
                                @endif
                                <button wire:click.stop="delete('{{ $notification->id }}')" class="text-gray-400 hover:text-red-500 p-1 rounded-md hover:bg-red-50 transition-colors" title="Hapus">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </li>
            @empty
                <li class="py-12 px-4 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada notifikasi</h3>
                    <p class="mt-1 text-sm text-gray-500">Saat ini Anda tidak memiliki notifikasi {{ $filter === 'unread' ? 'baru' : '' }}.</p>
                </li>
            @endforelse
        </ul>
        
        @if($notifications->hasPages())
            <div class="px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $notifications->links() }}
            </div>
        @endif
    </div>
</div>
