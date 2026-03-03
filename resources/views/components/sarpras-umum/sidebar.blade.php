<div
    x-show="sidebarOpen"
    x-transition.opacity
    class="fixed inset-0 bg-black/50 z-30 lg:hidden"
    @click="sidebarOpen = false">
</div>

<aside
    :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full lg:translate-x-0 lg:w-20'"
    class="flex-shrink-0 z-40 h-full bg-[#272125] text-white flex flex-col transition-all duration-300 fixed lg:static w-64">

    <div class="py-5 border-b border-white/10 flex items-center gap-3 flex-shrink-0" :class="sidebarOpen ? 'px-6' : 'lg:px-4 lg:justify-center px-6'">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
        <div x-show="sidebarOpen" x-transition class="overflow-hidden lg:block" :class="sidebarOpen ? '' : 'lg:hidden'">
            <h2 class="text-sm font-semibold leading-tight whitespace-nowrap">Sarana Umum</h2>
            <p class="text-xs text-white/60">Panel Sarpras</p>
        </div>
    </div>

    <nav class="flex-1 py-6 space-y-4 text-sm overflow-y-auto" :class="sidebarOpen ? 'px-4' : 'lg:px-2 px-4'">
        <a href="{{ route('sarana-umum.dashboard') }}"
           class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('sarana-umum.dashboard') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
            <x-heroicon-o-home class="w-5 h-5 flex-shrink-0"/>
            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Dashboard</span>
        </a>

        <div>
            <p class="text-xs uppercase text-white/40 px-3 mb-2" x-show="sidebarOpen">Manajemen Sarana</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('sarana-umum.data-sarana.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('sarana-umum.data-sarana.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-building-office class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Data Sarana</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sarana-umum.data-sarana.import') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('sarana-umum.data-sarana.import*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-arrow-down-tray class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Import Sarana</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sarana-umum.kategori-sarana.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('sarana-umum.kategori-sarana.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-tag class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Kategori Sarana</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sarana-umum.data-ruangan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('sarana-umum.data-ruangan.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-map-pin class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Data Ruangan</span>
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="text-xs uppercase text-white/40 px-3 mb-2" x-show="sidebarOpen">Data Master</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('sarana-umum.petugas-sarpras.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('sarana-umum.petugas-sarpras.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-users class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Petugas Sarpras</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sarana-umum.data-guru.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('sarana-umum.data-guru.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-academic-cap class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Data Guru</span>
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="text-xs uppercase text-white/40 px-3 mb-2" x-show="sidebarOpen">Laporan</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('sarana-umum.maintenance-log.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('sarana-umum.maintenance-log.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-wrench-screwdriver class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Maintenance Log</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sarana-umum.preventive-maintenance.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('sarana-umum.preventive-maintenance.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-calendar-days class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Preventive</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sarana-umum.berita-acara.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('sarana-umum.berita-acara.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-document-text class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Berita Acara</span>
                    </a>
                </li>
            </ul>
        </div>

        <div>
            <p class="text-xs uppercase text-white/40 px-3 mb-2" x-show="sidebarOpen">Sistem</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('sarana-umum.backup.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('sarana-umum.backup.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-server-stack class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Backup Database</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sarana-umum.audit-log.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('sarana-umum.audit-log.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-shield-check class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Audit Log</span>
                    </a>
                </li>
            </ul>
        </div>

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

    <div class="py-4 border-t border-white/10 text-xs text-white/50 flex-shrink-0" :class="sidebarOpen ? 'px-6' : 'px-2 text-center'">
        <span x-show="sidebarOpen">© {{ date('Y') }} Sistem Sarana Umum</span>
        <span x-show="!sidebarOpen">©{{ date('y') }}</span>
    </div>
</aside>
