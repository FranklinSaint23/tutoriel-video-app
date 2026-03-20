<x-app-layout>
    <div class="py-12 bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-6">
                <div>
                    <h1 class="text-4xl font-black text-white mb-2">Découvrir les <span class="text-indigo-500">Tutos</span></h1>
                    <p class="text-gray-400">Apprends le développement avec des experts passionnés.</p>
                </div>

                <form action="{{ route('videos.index') }}" method="GET" class="relative w-full md:w-96">
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Que veux-tu apprendre aujourd'hui ?"
                        class="w-full bg-gray-900 border-gray-800 text-white rounded-full pl-5 pr-12 py-3 focus:ring-2 focus:ring-indigo-500 transition-all">
                    <button type="submit" class="absolute right-3 top-2 text-gray-500 hover:text-white">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @forelse($videos as $video)
                    <div class="group cursor-pointer">
                        {{-- Image de la vidéo avec effet de zoom --}}
                        <a href="{{ route('videos.show', $video->slug) }}" class="block relative overflow-hidden rounded-2xl aspect-video border border-gray-800 bg-gray-900 mb-3">
                            <img src="{{ $video->thumbnail_url }}" 
                                class="w-full h-full object-cover transition duration-500 group-hover:scale-110" 
                                alt="{{ $video->title }}">
                            
                            {{-- Overlay Play au survol --}}
                            <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="bg-indigo-600 p-4 rounded-full text-white shadow-xl transform scale-75 group-hover:scale-100 transition-transform duration-300">
                                    <svg class="w-8 h-8 fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </div>

                            <span class="absolute bottom-2 right-2 bg-black/80 text-white text-[10px] px-2 py-1 rounded font-bold uppercase tracking-tighter">
                                {{ $video->level }}
                            </span>
                        </a>

                        {{-- Infos sous la vidéo --}}
                        <div class="px-1">
                            <div class="flex items-start gap-3">
                                {{-- Avatar fictif ou initiales --}}
                                <div class="w-10 h-10 rounded-full bg-indigo-900/50 flex-shrink-0 flex items-center justify-center text-indigo-400 font-bold text-xs border border-indigo-500/30">
                                    {{ substr($video->user->name, 0, 2) }}
                                </div>
                                <div>
                                    <h3 class="text-white font-bold text-sm leading-tight line-clamp-2 group-hover:text-indigo-400 transition">
                                        {{ $video->title }}
                                    </h3>
                                    <p class="text-gray-500 text-xs mt-1">{{ $video->user->name }} • {{ $video->category->name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center">
                        <p class="text-gray-500 text-lg italic">Aucun tutoriel ne correspond à ta recherche pour le moment.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-16 text-gray-400">
                {{ $videos->links() }}
            </div>

        </div>
    </div>
</x-app-layout>