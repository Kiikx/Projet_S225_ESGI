@props(['value', 'required' => false])

<label {{ $attributes->merge(['class' => 'block font-semibold text-sm text-neutral-700 mb-2']) }}>
    {{ $value ?? $slot }}
    @if($required)
        <span class="text-red-500 ml-1">*</span>
    @endif
</label>
