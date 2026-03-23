<x-app-layout>
    <div class="py-12 bg-gray-950 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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
                    <p class="text-gray-500 italic">Vous n'avez pas encore partagé de tutoriels.</p>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($myVideos as $video)
                            @endforeach
                    </div>
                @endif
            </div>

            <hr class="border-gray-800 mb-12">

            <div>
                <h3 class="text-xl font-bold text-red-500 mb-6 flex items-center gap-2">
                    <span class="w-2 h-6 bg-red-500 rounded-full"></span>
                    Ma Bibliothèque de Favoris
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @forelse($favoriteVideos as $video)
                        @empty
                        <p class="text-gray-500 italic">Aucun coup de cœur pour le moment.</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>