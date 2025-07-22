<x-guest-layout>
    <div class="max-w-lg mx-auto bg-white p-8 rounded-lg shadow-lg text-center">
        <div class="text-6xl mb-4">✅</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-4">Invitation déclinée</h1>
        <p class="text-gray-600 mb-6">
            Vous avez décliné l'invitation au projet. Aucune action supplémentaire n'est requise.
        </p>
        
        <div class="pt-4">
            <a href="{{ route('login') }}" 
               class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition">
                Retour à la connexion
            </a>
        </div>
    </div>
</x-guest-layout>
