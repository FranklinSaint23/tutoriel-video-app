<x-app-layout>
    <div class="py-12 bg-gray-950 min-h-screen">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Retour au Dashboard --}}
            <div class="mb-6">
                <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-indigo-400 transition text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Retour au Dashboard
                </a>
            </div>

            <div class="bg-gray-900 border border-gray-800 rounded-3xl overflow-hidden shadow-2xl">
                {{-- Header du formulaire --}}
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8">
                    <h2 class="text-2xl font-black text-white uppercase tracking-tight">Modifier le Tutoriel</h2>
                    <p class="text-indigo-100 text-sm opacity-80">Mettez à jour les informations de votre cours en quelques clics.</p>
                </div>

                <form action="{{ route('videos.update', $video) }}" method="POST" class="p-8 space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Titre --}}
                    <div>
                        <label for="title" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Titre du tutoriel</label>
                        <input type="text" name="title" id="title" 
                            value="{{ old('title', $video->title) }}"
                            class="w-full bg-gray-800 border-gray-700 rounded-xl text-white focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm"
                            placeholder="Ex: Maîtriser Laravel en 30min">
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Catégorie --}}
                        <div>
                            <label for="category_id" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Catégorie</label>
                            <select name="category_id" id="category_id" 
                                class="w-full bg-gray-800 border-gray-700 rounded-xl text-white focus:border-indigo-500 focus:ring-indigo-500 transition">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $video->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Niveau --}}
                        <div>
                            <label for="level" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Niveau de difficulté</label>
                            <select name="level" id="level" 
                                class="w-full bg-gray-800 border-gray-700 rounded-xl text-white focus:border-indigo-500 focus:ring-indigo-500 transition">
                                <option value="débutant" {{ $video->level == 'débutant' ? 'selected' : '' }}>Débutant</option>
                                <option value="intermédiaire" {{ $video->level == 'intermédiaire' ? 'selected' : '' }}>Intermédiaire</option>
                                <option value="avancé" {{ $video->level == 'avancé' ? 'selected' : '' }}>Avancé</option>
                            </select>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Description</label>
                        <textarea name="description" id="description" rows="5" 
                            class="w-full bg-gray-800 border-gray-700 rounded-xl text-white focus:border-indigo-500 focus:ring-indigo-500 transition shadow-sm"
                            placeholder="Détaillez le contenu de votre vidéo...">{{ old('description', $video->description) }}</textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="pt-4 flex items-center justify-end gap-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-white transition text-sm font-bold">Annuler</a>
                        <button type="submit" 
                            class="px-8 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-xl shadow-lg shadow-indigo-500/20 transition-all transform hover:-translate-y-1">
                            Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>

            {{-- Note de sécurité --}}
            <p class="mt-8 text-center text-gray-600 text-xs">
                Seul l'auteur original, <span class="text-gray-400 font-bold">{{ auth()->user()->name }}</span>, peut modifier ce contenu.
            </p>
        </div>
    </div>
</x-app-layout>