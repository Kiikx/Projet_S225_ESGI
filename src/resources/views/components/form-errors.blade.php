@php
    // Champs à exclure des erreurs générales (affichées directement sous les inputs)
    $excludedFields = [
        'name', 'email', 'password', 'password_confirmation', 'current_password', 'token', 'user_id',
        'title', 'description', 'priority_id', 'status_id', 'categories', 'assignees'
    ];
    
    // Récupérer toutes les erreurs sauf celles des champs spécifiques
    $generalErrors = [];
    foreach ($errors->keys() as $key) {
        if (!in_array($key, $excludedFields)) {
            $generalErrors = array_merge($generalErrors, $errors->get($key));
        }
    }
@endphp

@if (count($generalErrors) > 0)
    <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                    {{ count($generalErrors) == 1 ? 'Une erreur s\'est produite :' : 'Plusieurs erreurs se sont produites :' }}
                </h3>
                <div class="mt-2 text-sm text-red-700">
                    <ul class="list-disc space-y-1 pl-5">
                        @foreach ($generalErrors as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endif
