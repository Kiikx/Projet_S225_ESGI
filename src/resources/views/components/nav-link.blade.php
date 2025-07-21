@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center pt-1 border-b-2 border-primary-500 text-sm font-medium leading-5 text-gray-900 focus:outline-none focus:border-primary-600 transition duration-150 ease-in-out'
            : 'inline-flex items-center pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-primary-300 focus:outline-none focus:text-gray-700 focus:border-primary-300 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
