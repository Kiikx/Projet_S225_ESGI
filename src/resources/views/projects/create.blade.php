<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-gray-800">
            Créer un projet
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-xl mx-auto bg-white p-6 rounded shadow-md">
            <form method="POST" action="{{ route('projects.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nom du projet</label>
                    <input type="text" name="name" id="name" required
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea name="description" id="description" rows="4"
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 text-white px-5 py-2 rounded hover:bg-blue-700 transition">
                        Créer le projet
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
