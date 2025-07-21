<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-primary-500 to-accent-500 border border-transparent rounded-xl font-semibold text-sm text-white shadow-lg hover:shadow-xl hover:from-primary-600 hover:to-accent-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 active:scale-95 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed']) }}>
    {{ $slot }}
</button>
