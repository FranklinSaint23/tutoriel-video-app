<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-indigo-400 leading-tight">
            {{ __('Mettre en ligne un tutoriel') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-950 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            {{-- Le conteneur principal --}}
            <div class="bg-gray-900 overflow-hidden shadow-2xl sm:rounded-xl border border-gray-800 p-8">
                
                {{-- Important : enctype="multipart/form-data" pour les fichiers --}}
                <form action="{{ route('admin.videos.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide">Titre de la vidéo</label>
                        <input type="text" name="title" value="{{ old('title') }}" 
                            class="w-full bg-gray-800 border-gray-700 text-white focus:ring-2 focus:ring-indigo-500 rounded-lg p-3 placeholder-gray-500"
                            placeholder="Ex: Apprendre Laravel en 10min">
                        @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide">Catégorie</label>
                            <select name="category_id" class="w-full bg-gray-800 border-gray-700 text-white focus:ring-2 focus:ring-indigo-500 rounded-lg p-3">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide">Niveau</label>
                            <select name="level" class="w-full bg-gray-800 border-gray-700 text-white focus:ring-2 focus:ring-indigo-500 rounded-lg p-3">
                                <option value="Débutant">Débutant</option>
                                <option value="Intermédiaire">Intermédiaire</option>
                                <option value="Avancé">Avancé</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide">Description</label>
                        <textarea name="description" rows="4" 
                            class="w-full bg-gray-800 border-gray-700 text-white focus:ring-2 focus:ring-indigo-500 rounded-lg p-3"
                            placeholder="Expliquez brièvement le contenu du tutoriel...">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide">Fichier Vidéo (MP4)</label>
                            <input type="file" name="video" 
                                class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 cursor-pointer">
                            @error('video') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-400 mb-2 uppercase tracking-wide">Miniature (Image)</label>
                            <input type="file" name="thumbnail" 
                                class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-gray-700 file:text-white hover:file:bg-gray-600 cursor-pointer">
                            @error('thumbnail') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-3 p-4 bg-gray-800/50 rounded-xl border border-gray-700">
                        <input type="checkbox" name="is_published" id="is_published" 
                            {{ old('is_published', $video->is_published ?? false) ? 'checked' : '' }}
                            class="w-5 h-5 text-indigo-600 rounded border-gray-700 focus:ring-indigo-500 bg-gray-900">
                        <label for="is_published" class="text-sm font-bold text-gray-300 uppercase tracking-widest cursor-pointer">
                            Rendre ce tutoriel public (Publier)
                        </label>
                    </div>

                    <div class="pt-6 border-t border-gray-800">
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-500 text-white font-black py-4 rounded-xl shadow-lg shadow-red-900/20 uppercase tracking-widest transition-all duration-300 transform hover:scale-[1.01]">
                            PUBLIER LA VIDÉO
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>