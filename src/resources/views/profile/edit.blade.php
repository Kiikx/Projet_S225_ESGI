<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-neutral-900 mb-3">Mon Profil</h1>
            <p class="text-neutral-600">Gérez vos informations personnelles et paramètres de sécurité</p>
        </div>
        
        <div class="space-y-8">
            <div class="glass-card rounded-2xl p-8 animate-appear">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="glass-card rounded-2xl p-8 animate-appear">
                @include('profile.partials.update-password-form')
            </div>

            <div class="glass-card rounded-2xl p-8 animate-appear">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>

    <!-- Modal de suppression de compte - positionné globalement -->
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-red-600 text-2xl">🚨</span>
                </div>
                <h2 class="text-xl font-bold text-neutral-900 mb-2">
                    Confirmer la suppression
                </h2>
                <p class="text-neutral-600">
                    Êtes-vous absolument certain de vouloir supprimer votre compte ?
                </p>
            </div>

            <div class="mb-6 p-4 bg-red-50 rounded-xl border border-red-200">
                <p class="text-sm text-red-800">
                    <strong>Attention :</strong> Cette action supprimera définitivement toutes vos données, projets, tâches et ne pourra pas être annulée.
                </p>
            </div>

            <x-form-group>
                <x-input-label for="password" :required="true">Confirmez avec votre mot de passe</x-input-label>
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    placeholder="Saisissez votre mot de passe"
                    required />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </x-form-group>

            <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-neutral-200">
                <button type="button" 
                        x-on:click="$dispatch('close')"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 bg-white border border-neutral-300 rounded-xl font-semibold text-sm text-neutral-700 shadow-sm hover:bg-neutral-50 hover:shadow-md hover:border-neutral-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 active:scale-95 transition-all duration-200">
                    <span class="mr-2">✖️</span>
                    Annuler
                </button>

                <button type="submit"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center px-6 py-3 bg-red-600 border border-transparent rounded-xl font-semibold text-sm text-white shadow-lg hover:shadow-xl hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:scale-95 transition-all duration-200">
                    <span class="mr-2">🗑️</span>
                    Supprimer définitivement
                </button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
