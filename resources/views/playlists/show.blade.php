<x-app-layout>
    <div class="py-6 bg-gray-950 min-h-screen">
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- En-tête de la Playlist --}}
            <div class="mb-8 flex items-center justify-between border-b border-gray-800 pb-6">
                <div>
                    <h1 class="text-3xl font-black text-white uppercase tracking-tighter">
                        <span class="text-indigo-500">Playlist :</span> {{ $playlist->name }}
                    </h1>
                    <p class="text-gray-500 text-sm mt-1">Progression : {{ $playlist->videos->count() }} modules au total</p>
                </div>
                <a href="{{ route('playlists.index') }}" class="text-gray-400 hover:text-white transition text-xs font-bold uppercase tracking-widest border border-gray-700 px-4 py-2 rounded-xl">
                    Retour aux playlists
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                {{-- ZONE LECTEUR (3/4 de l'écran) --}}
                <div class="lg:col-span-3">
                    @if($currentVideo)
                        <div class="bg-black rounded-3xl overflow-hidden shadow-2xl border border-gray-800 aspect-video mb-6">
                            <video 
                                key="{{ $currentVideo->id }}"
                                controls 
                                controlsList="nodownload" 
                                poster="{{ $currentVideo->thumbnail_url }}" 
                                class="w-full h-full object-contain">
                                <source src="{{ asset(ltrim($currentVideo->video_url, '/')) }}" type="video/mp4">
                            </video>
                        </div>
                        
                        <div class="bg-gray-900 rounded-3xl p-8 border border-gray-800 shadow-sm">
                            <h2 class="text-2xl font-black text-white mb-4 uppercase tracking-tight">{{ $currentVideo->title }}</h2>
                            <div class="text-gray-400 text-sm leading-relaxed">
                                {!! nl2br(e($currentVideo->description)) !!}
                            </div>
                        </div>
                    @else
                        <div class="bg-gray-900 border border-dashed border-gray-700 rounded-3xl p-20 text-center">
                            <p class="text-gray-500">Cette playlist est vide.</p>
                        </div>
                    @endif
                </div>

                {{-- LISTE DES VIDÉOS (1/4 de l'écran) --}}
                <div class="lg:col-span-1">
                    <div class="bg-gray-900 border border-gray-800 rounded-3xl overflow-hidden sticky top-6">
                        <div class="p-5 border-b border-gray-800 bg-gray-800/50">
                            <h3 class="text-white font-black uppercase text-xs tracking-widest">Contenu du cours</h3>
                        </div>
                        
                        <div class="max-h-[70vh] overflow-y-auto custom-scrollbar">
                            @foreach($playlist->videos as $index => $video)
                                <div class="relative group border-b border-gray-800"> {{-- Conteneur parent --}}
                                    
                                    {{-- Lien principal vers la vidéo --}}
                                    <a href="{{ route('playlists.view', ['playlist' => $playlist->id, 'video' => $video->id]) }}" 
                                    class="flex items-center gap-4 p-4 transition-all hover:bg-gray-800/50 {{ $currentVideo && $currentVideo->id == $video->id ? 'bg-indigo-600/10 border-l-4 border-l-indigo-500' : '' }}">
                                        
                                        {{-- Index 01, 02... --}}
                                        <div class="text-gray-600 font-bold text-xs">
                                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                        </div>
                                        
                                        {{-- Thumbnail avec overlay si actif --}}
                                        <div class="relative w-20 h-12 flex-shrink-0 rounded-lg overflow-hidden border border-gray-700">
                                            <img src="{{ $video->thumbnail_url }}" class="w-full h-full object-cover">
                                            @if($currentVideo && $currentVideo->id == $video->id)
                                                <div class="absolute inset-0 bg-indigo-600/40 flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-white fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Titre de la vidéo --}}
                                        <div class="flex-1 pr-6"> {{-- Padding à droite pour ne pas chevaucher la croix --}}
                                            <h4 class="text-white text-[11px] font-bold line-clamp-2 leading-tight {{ $currentVideo && $currentVideo->id == $video->id ? 'text-indigo-400' : '' }}">
                                                {{ $video->title }}
                                            </h4>
                                        </div>
                                    </a>

                                    {{-- Bouton de suppression (La Croix) --}}
                                    <form action="{{ route('playlists.remove-video', [$playlist->id, $video->id]) }}" 
                                        method="POST" 
                                        class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300"
                                        onsubmit="return confirm('Retirer cette vidéo de la playlist ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 text-gray-500 hover:text-red-500 bg-gray-900/80 rounded-md backdrop-blur-sm border border-gray-700 shadow-xl">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </form>

                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>