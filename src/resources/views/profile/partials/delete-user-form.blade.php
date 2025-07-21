<section>
    <header class="mb-6">
        <h2 class="text-xl font-semibold text-red-900 mb-2 flex items-center gap-2">
            🚨 Zone dangereuse
        </h2>
        <p class="text-neutral-600">
            Une fois supprimé, votre compte et toutes ses données seront définitivement perdues. Téléchargez vos données importantes avant de procéder.
        </p>
    </header>

    <div class="p-6 bg-red-50 border border-red-200 rounded-xl">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                    <span class="text-red-600 text-lg">⚠️</span>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="font-medium text-red-900 mb-2">Suppression du compte</h3>
                <p class="text-sm text-red-700 mb-4">
                    Cette action est irréversible. Toutes vos données, projets et tâches seront définitivement supprimés.
                </p>
                <button
                    x-data=""
                    x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                    class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-xl font-semibold text-sm text-white shadow-lg hover:shadow-xl hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 active:scale-95 transition-all duration-200">
                    <span class="mr-2">🗑️</span>
                    Supprimer mon compte
                </button>
            </div>
        </div>
    </div>

</section>
