<!-- OVERLAY (mobile) - muncul saat sidebar terbuka, klik untuk tutup -->
<div 
    x-show="sidebarOpen"
    x-transition.opacity
    class="fixed inset-0 bg-black/50 z-30 lg:hidden"
    @click="sidebarOpen = false">
</div>

<!-- SIDEBAR -->
<aside 
    :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full lg:translate-x-0 lg:w-20'"
    class="flex-shrink-0 z-40 h-full bg-[#272125] text-white flex flex-col transition-all duration-300 fixed lg:static w-64">

    <!-- HEADER -->
    <div class="py-5 border-b border-white/10 flex items-center gap-3 flex-shrink-0" :class="sidebarOpen ? 'px-6' : 'lg:px-4 lg:justify-center px-6'">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
        <div x-show="sidebarOpen" x-transition class="overflow-hidden lg:block" :class="sidebarOpen ? '' : 'lg:hidden'">
            <h2 class="text-sm font-semibold leading-tight whitespace-nowrap">Laboratorium Komputer</h2>
            <p class="text-xs text-white/60">Panel Laboran</p>
        </div>
    </div>

    <!-- MENU -->
    <nav class="flex-1 py-6 space-y-4 text-sm overflow-y-auto" :class="sidebarOpen ? 'px-4' : 'lg:px-2 px-4'">

        <!-- DASHBOARD -->
        <a href="{{ route('laboran.index') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('laboran.index') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
            <x-heroicon-o-home class="w-5 h-5 flex-shrink-0"/>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Dashboard</span>
        </a>

        <!-- MASTER DATA -->
        <div>
            <p class="text-xs uppercase text-white/40 px-3 mb-2" x-show="sidebarOpen">Master Data</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('laboran.laboratorium.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('laboran.laboratorium.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-building-office class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Data Laboratorium</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('laboran.petugas.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('laboran.petugas.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-users class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Petugas Laboran</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('laboran.kategori-perangkat.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('laboran.kategori-perangkat.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-tag class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Kategori Perangkat</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- PERANGKAT -->
        <div>
            <p class="text-xs uppercase text-white/40 px-3 mb-2" x-show="sidebarOpen">Perangkat</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('laboran.unit-komputer.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('laboran.unit-komputer.*') || request()->routeIs('laboran.komponen-perangkat.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-computer-desktop class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Unit Komputer</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('laboran.maintenance-log.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('laboran.maintenance-log.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-wrench-screwdriver class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Maintenance Log</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- AKSI -->
        <div>
            <p class="text-xs uppercase text-white/40 px-3 mb-2" x-show="sidebarOpen">Aksi</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('dashboard.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition hover:bg-white/10" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-arrow-left-circle class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Kembali ke Portal</span>
                    </a>
                </li>
            </ul>
        </div>

    </nav>

    <!-- FOOTER -->
    <div class="py-4 border-t border-white/10 text-xs text-white/50 flex-shrink-0" :class="sidebarOpen ? 'px-6' : 'px-2 text-center'">
        <span x-show="sidebarOpen">© {{ date('Y') }} Sistem Laboratorium</span>
        <span x-show="!sidebarOpen">©{{ date('y') }}</span>
    </div>
</aside>