@props(['title', 'value', 'icon', 'color' => 'gold', 'change' => null, 'trend' => null])

@php
    $iconColorClasses = match($color) {
        'gold' => 'text-[#C8A84B] bg-[#C8A84B]/10',
        'green' => 'text-green-600 bg-green-100',
        'red' => 'text-red-600 bg-red-100',
        'blue' => 'text-blue-600 bg-blue-100',
        default => 'text-gray-600 bg-gray-100',
    };
@endphp

<div class="bg-white rounded-lg shadow-sm border border-gray-100 p-5">
    <div class="flex items-center">
        <div class="flex-shrink-0 p-3 rounded-md {{ $iconColorClasses }}">
            {{ $icon }}
        </div>
        <div class="ml-4 w-full flex-1">
            <h3 class="text-sm font-medium text-gray-500 truncate cursor-help" title="{{ $title }}">{{ $title }}</h3>
            <div class="mt-1 flex items-baseline justify-between">
                <p class="text-2xl font-bold text-gray-900">{{ $value }}</p>
                
                @if($change)
                    <p class="flex items-baseline text-sm font-semibold {{ $trend === 'up' ? 'text-green-600' : ($trend === 'down' ? 'text-red-600' : 'text-gray-500') }}">
                        @if($trend === 'up')
                            <svg class="w-3 h-3 self-center shrink-0 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                            </svg>
                        @elseif($trend === 'down')
                            <svg class="w-3 h-3 self-center shrink-0 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                            </svg>
                        @endif
                        {{ $change }}
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>
