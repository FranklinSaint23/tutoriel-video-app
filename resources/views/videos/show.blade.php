<x-app-layout>
    <div class="py-6 bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-2">
                    
                    {{-- 1. Le Lecteur Vidéo --}}
                    <div class="bg-black rounded-2xl overflow-hidden shadow-2xl border border-gray-800 aspect-video mb-6">
                        <video 
                            controls 
                            controlsList="nodownload" 
                            poster="{{ $video->thumbnail_url }}" 
                            class="w-full h-full object-contain focus:outline-none">
                            <source src="{{ asset(ltrim($video->video_url, '/')) }}" type="video/mp4">
                            Votre navigateur ne supporte pas la lecture de vidéos.
                        </video>
                    </div>

                    {{-- 2. Détails de la vidéo & Boutons d'interaction --}}
                    <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800 shadow-sm">
                        
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                            <h1 class="text-2xl font-black text-white leading-tight uppercase tracking-tight">
                                {{ $video->title }}
                            </h1>
                            
                            <div class="flex items-center gap-3">
                                {{-- Bouton de Vote (Like) --}}
                                <form action="{{ route('videos.like', $video) }}" method="POST">
                                    @csrf
                                    <button type="submit" 
                                        class="flex items-center gap-2 px-5 py-2.5 rounded-full transition-all duration-300 border {{ auth()->user() && auth()->user()->likedVideos->contains($video->id) ? 'bg-red-600/10 text-red-500 border-red-500/50 shadow-[0_0_15px_rgba(239,68,68,0.1)]' : 'bg-gray-800 text-gray-400 border-gray-700 hover:text-white hover:bg-gray-700' }}">
                                        
                                        <svg class="w-6 h-6 {{ auth()->user() && auth()->user()->likedVideos->contains($video->id) ? 'fill-current' : 'fill-none' }}" 
                                             stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                        </svg>
                                        
                                        <span class="font-bold text-sm">
                                            {{ $video->likedByUsers->count() }}
                                        </span>
                                    </button>
                                </form>

                                {{-- Badge de niveau --}}
                                <span class="px-3 py-1 bg-indigo-900/30 text-indigo-400 text-xs font-bold rounded-full border border-indigo-500/20 uppercase tracking-widest">
                                    {{ $video->level }}
                                </span>
                            </div>
                        </div>

                        {{-- 3. Infos Auteur --}}
                        <div class="flex items-center gap-4 pb-6 border-b border-gray-800">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white font-black shadow-lg">
                                {{ strtoupper(substr($video->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <p class="text-white font-bold text-lg">{{ $video->user->name }}</p>
                                <p class="text-gray-500 text-xs">
                                    Posté le {{ $video->created_at->format('d M Y') }} • 
                                    <span class="text-indigo-400 font-medium">{{ $video->category->name }}</span>
                                </p>
                            </div>
                        </div>

                        {{-- 4. Description --}}
                        <div class="mt-6">
                            <h3 class="text-gray-400 text-xs font-bold uppercase mb-2 tracking-widest">Description du cours</h3>
                            <div class="text-gray-300 leading-relaxed">
                                {!! nl2br(e($video->description ?: 'Aucune description détaillée pour ce tutoriel.')) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-1">
                    <div class="sticky top-6">
                        <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                            <div class="w-2 h-8 bg-indigo-600 rounded-full"></div>
                            Vidéos recommandées
                        </h2>

                        <div class="space-y-5">
                            @forelse($suggestions as $suggest)
                                <a href="{{ route('videos.show', $suggest->slug) }}" class="flex gap-4 group">
                                    {{-- Thumbnail suggestion --}}
                                    <div class="relative w-36 h-20 flex-shrink-0 overflow-hidden rounded-xl border border-gray-800 bg-gray-900 shadow-lg">
                                        <img src="{{ $suggest->thumbnail_url }}" 
                                             class="w-full h-full object-cover group-hover:scale-110 transition duration-500" 
                                             alt="{{ $suggest->title }}">
                                        <div class="absolute inset-0 bg-indigo-600/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        <span class="absolute bottom-1 right-1 bg-black/80 text-[9px] text-white px-1.5 py-0.5 rounded font-bold uppercase">
                                            {{ $suggest->level }}
                                        </span>
                                    </div>
                                    
                                    {{-- Info suggestion --}}
                                    <div class="flex-1">
                                        <h3 class="text-white font-bold text-sm line-clamp-2 group-hover:text-indigo-400 transition-colors leading-snug">
                                            {{ $suggest->title }}
                                        </h3>
                                        <p class="text-gray-500 text-[11px] mt-1">{{ $suggest->user->name }}</p>
                                        <p class="text-indigo-500/70 text-[10px] font-black uppercase tracking-tighter">{{ $suggest->category->name }}</p>
                                    </div>
                                </a>
                            @empty
                                <div class="bg-gray-900/50 rounded-xl p-6 border border-gray-800 border-dashed text-center text-gray-600">
                                    <p class="text-sm italic">Aucun autre tuto en {{ $video->category->name }}</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- Petit bouton de retour --}}
                        <div class="mt-10">
                            <a href="{{ route('videos.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-indigo-400 transition text-sm font-bold uppercase tracking-widest">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                Retour à la galerie
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>