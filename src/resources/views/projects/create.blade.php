<x-app-layout>
    <x-form-container 
        title="Nouveau projet"
        subtitle="Créez votre projet et invitez votre équipe">
        
        <form method="POST" action="{{ route('projects.store') }}" class="space-y-6">
            @csrf

            <x-form-group>
                <x-input-label for="name" :required="true">Nom du projet</x-input-label>
                <x-text-input 
                    id="name" 
                    name="name" 
                    type="text" 
                    value="{{ old('name') }}"
                    placeholder="Ex: Site web e-commerce"
                    required />
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </x-form-group>

            <x-form-group>
                <x-input-label for="description">Description</x-input-label>
                <x-textarea-input 
                    id="description" 
                    name="description" 
                    placeholder="Décrivez votre projet, ses objectifs et son contexte..."
                    rows="4">{{ old('description') }}</x-textarea-input>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-neutral-500 mt-1">Une bonne description aide votre équipe à comprendre le projet</p>
            </x-form-group>

            <div class="flex justify-center pt-6 border-t border-neutral-200">
                <x-primary-button>
                    <span class="text-lg mr-2">🚀</span>
                    Créer le projet
                </x-primary-button>
            </div>
        </form>
    </x-form-container>
</x-app-layout>
