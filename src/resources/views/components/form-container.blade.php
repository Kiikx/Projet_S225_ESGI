@props(['title' => null, 'subtitle' => null])

<div class="max-w-2xl mx-auto px-4 py-8">
    @if($title || $subtitle)
        <div class="mb-8 text-center">
            @if($title)
                <h1 class="text-3xl font-bold text-neutral-900 mb-3">{{ $title }}</h1>
            @endif
            @if($subtitle)
                <p class="text-neutral-600">{{ $subtitle }}</p>
            @endif
        </div>
    @endif
    
    <div class="glass-card rounded-2xl p-8 animate-appear">
        {{ $slot }}
    </div>
</div>
