@props(['disabled' => false, 'rows' => 4])

<textarea @disabled($disabled) rows="{{ $rows }}" {{ $attributes->merge(['class' => 'w-full px-4 py-3 bg-white border border-neutral-300 rounded-xl text-neutral-900 placeholder-neutral-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200 hover:border-neutral-400 disabled:bg-neutral-100 disabled:cursor-not-allowed shadow-sm resize-none']) }}>{{ $slot }}</textarea>
