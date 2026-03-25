{{-- Navigation optimisée pour Saint Tutos --}}
<nav x-data="{ open: false }" class="bg-gray-900 border-b border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            {{-- Partie Gauche : Logo --}}
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-indigo-500" />
                    </a>
                </div>
            </div>

            {{-- Partie Droite : Notifications + Profil --}}
            <div class="hidden sm:flex sm:items-center sm:ms-6 gap-2">
                
                {{-- 1. Bouton Notification --}}
                <div class="relative flex items-center">
                    <button class="relative p-2 text-gray-400 hover:text-indigo-500 transition-all duration-300 focus:outline-none group">
                        {{-- Icône Cloche --}}
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31c.55-.254.943-.797.943-1.393V9a8.25 8.25 0 0 0-15.75-2.25v5.377c0 .596.392 1.14.943 1.393a23.848 23.848 0 0 0 5.454 1.31m5.714 0m-5.714 0a3 3 0 1 1 5.714 0M3.124 7.5A8.969 8.969 0 0 1 5.292 3m13.416 0a8.969 8.969 0 0 1 2.168 4.5" />
                        </svg>

                        {{-- Badge animé (Optionnel: ajouter une condition Blade ici si tu as des notifs en BDD) --}}
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-indigo-600 border border-gray-950"></span>
                            </span>
                        @endif


                        {{-- Tooltip au survol --}}
                        <span class="absolute -bottom-10 left-1/2 -translate-x-1/2 bg-gray-800 text-white text-[10px] font-black px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none uppercase tracking-widest border border-gray-700 shadow-xl">
                            Notifications
                        </span>
                    </button>
                </div>

                {{-- 2. Dropdown Profil --}}
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-bold rounded-xl text-gray-400 bg-gray-800/50 hover:text-white hover:bg-gray-800 transition ease-in-out duration-150">
                            <div>
                                @if(Auth::check())
                                    {{ Auth::user()->name }}
                                @else
                                    Invité
                                @endif
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Mon Profil') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('videos.index')">
                            {{ __('Découvrir les tutos') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('dashboard')">
                            {{ __('Mon Dashboard') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('playlists.index')">
                            {{ __('Mes Playlists') }}
                        </x-dropdown-link>

                        <div class="border-t border-gray-800 mt-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();"
                                    class="text-red-400 hover:text-red-500">
                                {{ __('Déconnexion') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>