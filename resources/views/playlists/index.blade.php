<x-app-layout>
    <div class="py-12 bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h1 class="text-3xl font-black text-white uppercase tracking-tighter flex items-center gap-3">
                        <div class="w-2 h-10 bg-indigo-600 rounded-full"></div>
                        Mes Playlists
                    </h1>
                    <p class="text-gray-500 text-sm mt-2">Retrouvez vos cours et tutoriels organisés par thématiques.</p>
                </div>
                <div class="flex">
                    <a href="{{ route('videos.index') }}" class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white text-sm font-bold rounded-lg transition border border-gray-700">
                        Découvrir tutos
                    </a>
                </div>
            </div>

            @if($playlists->isEmpty())
                <div class="bg-gray-900 border border-gray-800 rounded-3xl p-20 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gray-800 rounded-full mb-6 text-gray-600">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <h2 class="text-xl font-bold text-white mb-2">Aucune playlist pour le moment</h2>
                    <p class="text-gray-500 mb-8 max-w-sm mx-auto">Commencez à organiser vos cours en créant votre première playlist depuis la page d'une vidéo.</p>
                    <a href="{{ route('videos.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-xl transition">
                        Parcourir les cours
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($playlists as $playlist)
                        <div class="group bg-gray-900 border border-gray-800 rounded-3xl overflow-hidden hover:border-indigo-500/50 transition-all duration-500 shadow-lg hover:shadow-indigo-500/10">
                            
                            {{-- Aperçu Visuel --}}
                            <div class="relative aspect-video bg-gray-750 flex items-center justify-center overflow-hidden">
                                @if($playlist->videos->isNotEmpty())
                                    <img src="{{ $playlist->videos->first()->thumbnail_url }}" class="w-full h-full object-cover opacity-40 group-hover:scale-110 transition duration-700">
                                @endif
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent"></div>
                                
                                <div class="absolute bottom-4 left-6 right-6 flex items-end justify-between">
                                    <div class="bg-indigo-600 text-white text-[10px] font-black px-3 py-1 rounded-full uppercase tracking-widest shadow-xl">
                                        {{ $playlist->videos_count }} {{ Str::plural('Vidéo', $playlist->videos_count) }}
                                    </div>
                                    
                                    <div class="w-10 h-10 rounded-full bg-indigo-600 backdrop-blur-md flex items-center justify-center text-white border border-white/20">
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>
                            </div>

                            {{-- Détails --}}
                            <div class="p-6">
                                <h3 class="text-xl font-black text-white mb-2 group-hover:text-indigo-400 transition-colors uppercase truncate">
                                    {{ $playlist->name }}
                                </h3>
                                <p class="text-gray-500 text-xs mb-6">Créée le {{ $playlist->created_at->format('d/m/Y') }}</p>
                                
                                <div class="flex items-center gap-3">
                                    <a href="{{ route('playlists.view', $playlist->id) }}" class="flex-1 text-center py-3 bg-gray-800 hover:bg-gray-700 text-white text-xs font-black rounded-xl transition border border-gray-700 uppercase tracking-widest">
                                        Voir le contenu
                                    </a>
                                    
                                    {{-- Suppression de la Playlist --}}
                                    <form action="{{ route('playlists.destroy', $playlist->id) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer cette playlist ? Cette action est irréversible.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-3 text-gray-600 hover:text-red-500 transition-colors duration-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>