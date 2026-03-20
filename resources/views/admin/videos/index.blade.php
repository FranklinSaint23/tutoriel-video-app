<x-app-layout>
    <x-slot name="header">
        {{-- bg-gray-900 = Fond très sombre / text-gray-100 = Texte presque blanc --}}
        <h2 class="font-bold text-2xl text-indigo-400 leading-tight">
            {{ __('Studio de Gestion') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-950 min-h-screen"> {{-- Fond de page noir profond --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- shadow-2xl = Grosse ombre pour décoller la carte / border-gray-800 = Bordure discrète --}}
            <div class="bg-gray-900 overflow-hidden shadow-2xl sm:rounded-xl border border-gray-800">
                <div class="p-8">
                    
                    <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                        <h3 class="text-xl font-semibold text-white">Vidéos publiées ({{ $videos->total() }})</h3>
                        
                        <div class="flex items-center gap-4">
                            <form action="{{ route('admin.videos.index') }}" method="GET" class="flex">
                                {{-- focus:ring-2 = Halo bleu quand on clique --}}
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    class="bg-gray-800 border-gray-700 text-gray-200 focus:ring-2 focus:ring-indigo-500 rounded-lg shadow-sm text-sm w-64" 
                                    placeholder="Rechercher un tuto...">
                                <button type="submit" class="ml-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white rounded-lg transition duration-200">
                                    Filtrer
                                </button>
                            </form>

                            <a href="{{ route('admin.videos.create') }}" 
                                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-lg shadow-lg shadow-emerald-900/20 transition-all">
                                + Ajouter
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto rounded-lg">
                        <table class="min-w-full">
                            {{-- On change l'entête pour du sombre --}}
                            <thead class="bg-gray-800/50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Aperçu</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Détails</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Auteur</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                @forelse($videos as $video)
                                    <tr class="hover:bg-gray-800/30 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="relative group">
                                                <img src="{{ $video->thumbnail_url }}" class="h-16 w-28 object-cover rounded-lg border border-gray-700 group-hover:border-indigo-500">
                                                {{-- Petite pastille de niveau --}}
                                                <span class="absolute bottom-1 right-1 bg-black/70 text-[10px] text-white px-1 rounded">
                                                    {{ $video->level }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-bold text-white">{{ $video->title }}</div>
                                            <div class="text-xs text-indigo-400">{{ $video->category->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-400">
                                            {{ $video->user->name }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end space-x-4">
                                                <a href="{{ route('admin.videos.edit', $video->id) }}" class="text-gray-400 hover:text-white transition">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </a>
                                                
                                                <form action="{{ route('admin.videos.destroy', $video->id) }}" method="POST" onsubmit="return confirm('Supprimer définitivement ?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">
                        {{ $videos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>