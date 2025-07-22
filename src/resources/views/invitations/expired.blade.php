<x-guest-layout>
    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg text-center">
        <div class="text-6xl mb-4">⏰</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Invitation expirée</h1>
        <p class="text-gray-600 mb-6">{{ $message }}</p>
        
        <div class="space-y-4">
            <p class="text-sm text-gray-500">
                Si vous pensez qu'il s'agit d'une erreur, contactez la personne qui vous a invité pour qu'elle vous renvoie une nouvelle invitation.
            </p>
            
            <div class="pt-4">
                <a href="{{ route('login') }}" 
                   class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                    Retour à la connexion
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
