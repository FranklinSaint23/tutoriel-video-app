<x-app-layout>
    <div class="py-12 bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- SECTION 1 : TES PROPRES UPLOADS --}}
            <div class="mb-12">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <span class="w-2 h-6 bg-indigo-500 rounded-full"></span>
                        Mes Tutoriels Mis en Ligne
                    </h3>
                    <div class="flex gap-2">
                    <a href="{{ route('videos.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-lg transition">
                        + Nouvel Upload
                    </a>
                    <a href="{{ route('videos.index') }}" class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white text-sm font-bold rounded-lg transition">
                        Découvrir tutos
                    </a>
                    </div>
                </div>

                @if($myVideos->isEmpty())
                    <div class="bg-gray-900/50 border border-dashed border-gray-800 rounded-2xl p-8 text-center">
                        <p class="text-gray-500 italic">Vous n'avez pas encore partagé de tutoriels.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($myVideos as $video)
                            <div class="bg-gray-900 rounded-2xl overflow-hidden border border-gray-800 group hover:border-indigo-500/50 transition duration-300 relative">
                                
                                {{-- 1. Overlay d'actions (Apparaît au survol) --}}
                                <div class="absolute top-3 right-3 z-10 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    {{-- Bouton Modifier --}}
                                    <a href="{{ route('videos.edit', $video->id) }}" 
                                    class="p-2 bg-blue-600/90 hover:bg-blue-500 text-white rounded-lg shadow-lg backdrop-blur-sm transition"
                                    title="Modifier ce tuto">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    {{-- Bouton Supprimer (Formulaire pour la sécurité) --}}
                                    <form action="{{ route('videos.destroy', $video->id) }}" method="POST" onsubmit="return confirm('Es-tu sûr de vouloir supprimer ce tutoriel ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 bg-red-600/90 hover:bg-red-500 text-white rounded-lg shadow-lg backdrop-blur-sm transition"
                                                title="Supprimer définitivement">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>

                                {{-- 2. Image et Lien --}}
                                <a href="{{ route('videos.show', $video->slug) }}" class="block relative aspect-video">
                                    <img src="{{ $video->thumbnail_url }}" class="w-full h-full object-cover group-hover:brightness-50 transition duration-500">
                                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                        <span class="text-white text-xs font-bold uppercase tracking-widest border border-white/50 px-3 py-1 rounded">Voir le rendu</span>
                                    </div>
                                </a>

                                {{-- 3. Infos du bas --}}
                                <div class="p-4 bg-gradient-to-b from-gray-900 to-black">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="text-white font-bold line-clamp-1 flex-1">{{ $video->title }}</h4>
                                        <span class="text-[10px] px-2 py-0.5 rounded bg-gray-800 text-gray-400 border border-gray-700">
                                            {{ strtoupper($video->level) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <p class="text-gray-500 text-xs italic">{{ $video->created_at->diffForHumans() }}</p>
                                        <div class="flex items-center gap-1 text-red-500 text-xs font-bold">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                                            {{ $video->likedByUsers->count() }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <hr class="border-gray-800 mb-12">

            {{-- SECTION 2 : TES FAVORIS --}}
            <div>
                <h3 class="text-xl font-bold text-red-500 mb-6 flex items-center gap-2">
                    <span class="w-2 h-6 bg-red-500 rounded-full"></span>
                    Ma Bibliothèque de Favoris
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($favoriteVideos as $video)
                        <div class="bg-gray-900 rounded-2xl overflow-hidden border border-gray-800 group hover:border-red-500/50 transition duration-300">
                            <a href="{{ route('videos.show', $video->slug) }}" class="block relative aspect-video">
                                <img src="{{ $video->thumbnail_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute top-2 right-2 bg-red-600 text-white p-1.5 rounded-full shadow-lg">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                                </div>
                            </a>
                            <div class="p-4">
                                <h4 class="text-white font-bold line-clamp-1">{{ $video->title }}</h4>
                                <p class="text-gray-500 text-xs mt-1">Par {{ $video->user->name }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full bg-gray-900/50 border border-dashed border-gray-800 rounded-2xl p-8 text-center">
                            <p class="text-gray-500 italic">Aucun coup de cœur pour le moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>