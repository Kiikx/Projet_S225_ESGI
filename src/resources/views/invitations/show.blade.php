<x-guest-layout>
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-lg text-center">
        <div class="text-6xl mb-4">📧</div>
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Invitation à rejoindre un projet</h1>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <h2 class="text-lg font-semibold text-blue-800 mb-2">{{ $invitation->project->name }}</h2>
            @if($invitation->project->description)
                <p class="text-blue-700 text-sm mb-3">{{ $invitation->project->description }}</p>
            @endif
            <p class="text-sm text-blue-600">
                Invitation de <strong>{{ $invitation->inviter->name }}</strong>
            </p>
        </div>
        
        <p class="text-gray-600 mb-6">
            Vous avez été invité à rejoindre ce projet sur Kanboard.
            @if(!Auth::check())
                <br><span class="text-sm text-gray-500">Vous devrez vous connecter ou créer un compte pour continuer.</span>
            @endif
        </p>

        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <form action="{{ route('invitations.accept', $invitation->token) }}" method="POST">
                @csrf
                <button type="submit" 
                        class="w-full sm:w-auto bg-green-500 hover:bg-green-600 text-white font-medium py-3 px-8 rounded-lg transition">
                    ✅ Accepter l'invitation
                </button>
            </form>
            <a href="{{ route('invitations.decline', $invitation->token) }}" 
               class="w-full sm:w-auto bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-8 rounded-lg transition text-center">
                ❌ Décliner
            </a>
        </div>
        
        <div class="mt-6 pt-4 border-t border-gray-200">
            <p class="text-xs text-gray-500">
                Cette invitation expire le {{ $invitation->expires_at->format('d/m/Y à H:i') }}
            </p>
        </div>
    </div>
</x-guest-layout>
