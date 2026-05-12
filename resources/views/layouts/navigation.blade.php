<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard')" :active="request()->routeIs('admin.dashboard') || request()->routeIs('user.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    
                    @if(auth()->user()->role === 'user')
                    <div class="hidden sm:flex sm:items-center sm:ml-6 gap-2">
                        <a href="{{ route('dermatology.index') }}" class="inline-flex items-center px-4 py-2 bg-[#FDF9F1] border border-[#BA7517]/20 rounded-full font-bold text-sm text-[#BA7517] hover:bg-[#BA7517] hover:text-white transition ease-in-out duration-150 {{ request()->routeIs('dermatology.*') ? 'bg-[#BA7517] text-white shadow-md' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            Dermatology
                        </a>
                        <a href="{{ route('user.loyalty.index') }}" class="inline-flex items-center px-4 py-2 bg-amber-50 border border-[#BA7517]/20 rounded-full font-bold text-sm text-[#BA7517] hover:bg-[#BA7517] hover:text-white transition ease-in-out duration-150 {{ request()->routeIs('user.loyalty.*') ? 'bg-[#BA7517] text-white shadow-md' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                            Loyalty
                        </a>
                        <a href="{{ route('karebla.index') }}" class="inline-flex items-center px-4 py-2 bg-[#F5E6C8]/30 border border-[#D4AF37]/30 rounded-full font-bold text-sm text-[#D4AF37] hover:bg-[#D4AF37] hover:text-white transition ease-in-out duration-150 {{ request()->routeIs('karebla.*') ? 'bg-[#D4AF37] text-white shadow-md' : '' }}">
                            ✨ Karebla Rewards
                        </a>
                        <a href="{{ route('konsultasi.index') }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-[var(--tx-secondary-light)] to-[var(--tx-tertiary-light)] border border-white/60 rounded-full font-bold text-sm text-[var(--tx-secondary)] hover:from-[var(--tx-secondary)] hover:to-[var(--tx-tertiary)] hover:text-white transition ease-in-out duration-150 shadow-sm {{ request()->routeIs('konsultasi.*') ? 'from-[var(--tx-secondary)] to-[var(--tx-tertiary)] text-white shadow-md' : '' }}">
                            🤖 Truevera AI
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="auth()->user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard')" :active="request()->routeIs('admin.dashboard') || request()->routeIs('user.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(auth()->user()->role === 'user')
            <x-responsive-nav-link :href="route('dermatology.index')" :active="request()->routeIs('dermatology.*')">
                {{ __('Dermatology') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('user.loyalty.index')" :active="request()->routeIs('user.loyalty.*')">
                {{ __('Loyalty') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('karebla.index')" :active="request()->routeIs('karebla.*')">
                ✨ {{ __('Karebla Rewards') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('konsultasi.index')" :active="request()->routeIs('konsultasi.*')">
                🤖 {{ __('Truevera AI') }}
            </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
