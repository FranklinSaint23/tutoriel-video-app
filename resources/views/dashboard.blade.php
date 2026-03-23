<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-white leading-tight">
            {{ __('Mon Espace Apprentissage') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <h3 class="text-xl font-bold text-indigo-400 mb-6 flex items-center gap-2">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                Mes Vidéos Favorites
            </h3>

            @if($favoriteVideos->isEmpty())
                <div class="bg-gray-900 rounded-2xl p-12 border border-gray-800 border-dashed text-center">
                    <p class="text-gray-500 mb-4 text-lg">Tu n'as pas encore de vidéos favorites.</p>
                    <a href="{{ route('videos.index') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition">
                        Parcourir les tutoriels
                    </a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($favoriteVideos as $video)
                        <div class="bg-gray-900 rounded-2xl overflow-hidden border border-gray-800 group hover:border-indigo-500/50 transition">
                            <a href="{{ route('videos.show', $video->slug) }}" class="block relative aspect-video">
                                <img src="{{ $video->thumbnail_url }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition">
                                    <svg class="w-12 h-12 text-white fill-current" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </div>
                            </a>
                            <div class="p-4">
                                <h4 class="text-white font-bold line-clamp-1 mb-1">{{ $video->title }}</h4>
                                <p class="text-gray-500 text-xs">{{ $video->category->name }} • Par {{ $video->user->name }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>
</x-app-layout>