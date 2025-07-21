<section>
    <header class="mb-6">
        <h2 class="text-xl font-semibold text-neutral-900 mb-2">
            📝 Informations personnelles
        </h2>
        <p class="text-neutral-600">
            Mettez à jour vos informations de compte et votre adresse e-mail
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
        @csrf
        @method('patch')

        <x-form-group>
            <x-input-label for="name" :required="true">Nom complet</x-input-label>
            <x-text-input 
                id="name" 
                name="name" 
                type="text" 
                :value="old('name', $user->name)" 
                placeholder="Votre nom complet"
                required 
                autofocus 
                autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </x-form-group>

        <x-form-group>
            <x-input-label for="email" :required="true">Adresse e-mail</x-input-label>
            <x-text-input 
                id="email" 
                name="email" 
                type="email" 
                :value="old('email', $user->email)" 
                placeholder="votre@email.com"
                required 
                autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <div class="flex items-start gap-3">
                        <span class="text-amber-600 text-lg">⚠️</span>
                        <div>
                            <p class="text-sm text-amber-800 font-medium">
                                Votre adresse e-mail n'est pas vérifiée
                            </p>
                            <button form="send-verification" 
                                class="mt-2 text-sm text-amber-700 hover:text-amber-900 underline font-medium transition-colors">
                                Renvoyer l'e-mail de vérification
                            </button>
                        </div>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-3 text-sm font-medium text-green-700 flex items-center gap-2">
                            <span>✓</span>
                            Un nouveau lien de vérification a été envoyé à votre adresse e-mail
                        </p>
                    @endif
                </div>
            @endif
        </x-form-group>

        <div class="flex items-center justify-between pt-6 border-t border-neutral-200">
            <div>
                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }"
                       x-show="show"
                       x-transition
                       x-init="setTimeout(() => show = false, 3000)"
                       class="text-sm text-green-700 font-medium flex items-center gap-2">
                        <span>✓</span>
                        Informations sauvegardées avec succès
                    </p>
                @endif
            </div>
            <x-primary-button>
                <span class="mr-2">💾</span>
                Sauvegarder
            </x-primary-button>
        </div>
    </form>
</section>
