@props(['title', 'subtitle' => null])

<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 pb-4 border-b border-gray-200">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
        @if($subtitle)
            <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
        @endif
    </div>

    @if(isset($actions))
        <div class="mt-4 md:mt-0 flex items-center space-x-3">
            {{ $actions }}
        </div>
    @endif
</div>
