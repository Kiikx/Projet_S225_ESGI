@props(['disabled' => false, 'multiple' => false])

@php
    // Récupérer le nom du champ depuis les attributs
    $fieldName = $attributes->get('name');
    // Vérifier s'il y a une erreur pour ce champ
    $hasError = $errors->has($fieldName);
    // Classes CSS de base
    $baseClasses = 'w-full px-4 py-3 bg-white rounded-xl text-neutral-900 focus:outline-none transition-all duration-200 shadow-sm disabled:bg-neutral-100 disabled:cursor-not-allowed';
    // Classes selon l'état d'erreur
    $stateClasses = $hasError 
        ? 'border-red-500 focus:ring-2 focus:ring-red-500 focus:border-red-500 hover:border-red-600'
        : 'border-neutral-300 focus:ring-2 focus:ring-primary-500 focus:border-transparent hover:border-neutral-400';
@endphp

<select @disabled($disabled) @if($multiple) multiple @endif {{ $attributes->merge(['class' => $baseClasses . ' ' . $stateClasses]) }}>
    {{ $slot }}
</select>
