@props(['title' => null, 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden']) }}>
    @if($title || isset($action))
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-start">
            <div>
                @if($title)
                    <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
                @endif
            </div>
            @if(isset($action))
                <div class="ml-4 flex-shrink-0">
                    {{ $action }}
                </div>
            @endif
        </div>
    @endif
    
    <div class="p-5">
        {{ $slot }}
    </div>
</div>
