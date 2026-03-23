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
                    <a href="{{ route('admin.videos.create') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-lg transition">
                        + Nouvel Upload
                    </a>
                </div>

                @if($myVideos->isEmpty())
                    <div class="bg-gray-900/50 border border-dashed border-gray-800 rounded-2xl p-8 text-center">
                        <p class="text-gray-500 italic">Vous n'avez pas encore partagé de tutoriels.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($myVideos as $video)
                            <div class="bg-gray-900 rounded-2xl overflow-hidden border border-gray-800 group hover:border-indigo-500/50 transition duration-300">
                                <a href="{{ route('videos.show', $video->slug) }}" class="block relative aspect-video">
                                    <img src="{{ $video->thumbnail_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                        <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                                    </div>
                                </a>
                                <div class="p-4">
                                    <h4 class="text-white font-bold line-clamp-1">{{ $video->title }}</h4>
                                    <p class="text-gray-500 text-xs mt-1">{{ $video->created_at->diffForHumans() }}</p>
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