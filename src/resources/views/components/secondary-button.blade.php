@props(['href' => null])

@if($href)
<a href="{{ $href }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-6 py-3 bg-white border border-neutral-300 rounded-xl font-semibold text-sm text-neutral-700 shadow-sm hover:bg-neutral-50 hover:shadow-md hover:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 active:scale-95 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</a>
@else
<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-white border border-neutral-300 rounded-xl font-semibold text-sm text-neutral-700 shadow-sm hover:bg-neutral-50 hover:shadow-md hover:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 active:scale-95 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
@endif
