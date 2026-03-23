<x-app-layout>
    <div class="py-6 bg-gray-950 min-h-screen" x-data="{ rating: 0, hoverRating: 0 }">
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
                    <div class="bg-gray-900 rounded-2xl p-6 border border-gray-800 shadow-sm mb-8">
                        
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                            <h1 class="text-2xl font-black text-white leading-tight uppercase tracking-tight">
                                {{ $video->title }}
                            </h1>
                            
                            <div class="flex items-center gap-3">
                                {{-- Système de Notation (Etoiles) --}}
                                <div class="flex items-center bg-gray-800 px-3 py-1.5 rounded-full border border-gray-700">
                                    <div class="flex items-center text-yellow-500 mr-2">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        <span class="ml-1 text-white font-bold text-sm">{{ $video->averageRating() }}</span>
                                    </div>
                                    <span class="text-gray-500 text-xs">({{ $video->ratings->count() }} avis)</span>
                                </div>

                                {{-- Gestion du Like --}}
                                @auth
                                    <form action="{{ route('videos.like', $video) }}" method="POST">
                                        @csrf
                                        <button type="submit" 
                                            class="flex items-center gap-2 px-5 py-2.5 rounded-full transition-all duration-300 border {{ auth()->user()->likedVideos->contains($video->id) ? 'bg-red-600/10 text-red-500 border-red-500/50' : 'bg-gray-800 text-gray-400 border-gray-700 hover:text-white hover:bg-gray-700' }}">
                                            <svg class="w-5 h-5 {{ auth()->user()->likedVideos->contains($video->id) ? 'fill-current' : 'fill-none' }}" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                            <span class="font-bold text-sm">{{ $video->likedByUsers->count() }}</span>
                                        </button>
                                    </form>
                                @endauth
                            </div>
                        </div>

                        {{-- 3. Infos Auteur --}}
                        <div class="flex items-center gap-4 pb-6 border-b border-gray-800">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white font-black">
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
                            <div class="text-gray-300 leading-relaxed text-sm">
                                {!! nl2br(e($video->description ?: 'Aucune description détaillée.')) !!}
                            </div>
                        </div>
                    </div>

                    {{-- 5. SECTION NOTATION & COMMENTAIRES --}}
                    <div class="space-y-6">
                        {{-- Noter la vidéo --}}
                        @auth
                            <div class="bg-indigo-900/10 border border-indigo-500/20 rounded-2xl p-6">
                                <h4 class="text-white font-bold mb-4 flex items-center gap-2 text-sm uppercase">
                                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                    Qu'avez-vous pensé de ce tutoriel ?
                                </h4>
                                <form action="{{ route('videos.rate', $video) }}" method="POST" class="flex items-center gap-4">
                                    @csrf
                                    <div class="flex gap-1">
                                        <template x-for="i in 5">
                                            <button type="submit" name="stars" :value="i" 
                                                @mouseenter="hoverRating = i" 
                                                @mouseleave="hoverRating = 0"
                                                class="transition-transform hover:scale-125 focus:outline-none">
                                                <svg class="w-8 h-8" :class="(hoverRating || rating) >= i ? 'text-yellow-500 fill-current' : 'text-gray-700 fill-none'" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            </button>
                                        </template>
                                    </div>
                                    <p class="text-gray-500 text-xs italic">Cliquez sur une étoile pour valider</p>
                                </form>
                            </div>
                        @endauth

                        {{-- Liste des Commentaires --}}
                        <div class="bg-gray-900 rounded-3xl p-8 border border-gray-800 shadow-sm">
                            <h3 class="text-xl font-bold text-white mb-8">Questions & Discussions</h3>

                            @auth
                                <form action="{{ route('comments.store', $video) }}" method="POST" class="mb-10">
                                    @csrf
                                    <textarea name="content" rows="3" 
                                        class="w-full bg-gray-800 border-gray-700 rounded-2xl text-white placeholder-gray-500 focus:ring-indigo-500 transition"
                                        placeholder="Une question ? Un remerciement ?"></textarea>
                                    <div class="flex justify-end mt-3">
                                        <button type="submit" class="px-8 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white font-black rounded-xl transition shadow-lg shadow-indigo-500/20">
                                            Publier
                                        </button>
                                    </div>
                                </form>
                            @endauth

                            <div class="space-y-6">
                                @forelse($video->comments as $comment)
                                    <div class="flex gap-4 p-5 rounded-2xl bg-gray-800/30 border border-gray-800 group transition hover:border-gray-700">
                                        <div class="w-10 h-10 rounded-full bg-indigo-900/50 flex items-center justify-center text-indigo-400 font-bold shrink-0 border border-indigo-500/20">
                                            {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-white font-bold text-sm">{{ $comment->user->name }}</span>
                                                <span class="text-gray-600 text-[10px]">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="text-gray-400 text-sm leading-relaxed">{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-10">
                                        <p class="text-gray-600 italic text-sm">Soyez le premier à poser une question !</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SIDEBAR : RECOMMANDATIONS --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-6">
                        <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2 uppercase tracking-tighter">
                            <div class="w-2 h-8 bg-indigo-600 rounded-full"></div>
                            Vidéos recommandées
                        </h2>

                        <div class="space-y-5">
                            @forelse($suggestions as $suggest)
                                <a href="{{ route('videos.show', $suggest->slug) }}" class="flex gap-4 group">
                                    <div class="relative w-32 h-20 flex-shrink-0 overflow-hidden rounded-xl border border-gray-800 bg-gray-900">
                                        <img src="{{ $suggest->thumbnail_url }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                                        <span class="absolute bottom-1 right-1 bg-black/80 text-[8px] text-white px-1 py-0.5 rounded font-bold uppercase tracking-widest">
                                            {{ $suggest->level }}
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="text-white font-bold text-xs line-clamp-2 group-hover:text-indigo-400 transition-colors leading-tight mb-1">
                                            {{ $suggest->title }}
                                        </h3>
                                        <p class="text-gray-500 text-[10px] mb-1">{{ $suggest->user->name }}</p>
                                        <p class="text-indigo-500/70 text-[9px] font-black uppercase tracking-widest">{{ $suggest->category->name }}</p>
                                    </div>
                                </a>
                            @empty
                                <div class="bg-gray-900/50 rounded-xl p-6 border border-gray-800 border-dashed text-center text-gray-600">
                                    <p class="text-sm italic">Aucune suggestion.</p>
                                </div>
                            @endforelse
                        </div>

                        <div class="mt-10 pt-6 border-t border-gray-800">
                            <a href="{{ route('videos.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-indigo-400 transition text-[10px] font-black uppercase tracking-[0.2em]">
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