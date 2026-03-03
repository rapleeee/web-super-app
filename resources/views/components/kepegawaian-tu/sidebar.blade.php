@php
    $userRole = auth()->user()?->role;
    $isPrivilegedUser = in_array($userRole, ['admin', 'pejabat'], true);
    $isTuModuleUser = in_array($userRole, ['laboran', 'staff', 'admin', 'pejabat'], true);
@endphp

<div
    x-show="sidebarOpen"
    x-transition.opacity
    class="fixed inset-0 bg-black/50 z-30 lg:hidden"
    @click="sidebarOpen = false">
</div>

<aside
    :class="sidebarOpen ? 'translate-x-0 w-64' : '-translate-x-full lg:translate-x-0 lg:w-20'"
    class="flex-shrink-0 z-40 h-full bg-[#1f2937] text-white flex flex-col transition-all duration-300 fixed lg:static w-64">

    <div class="py-5 border-b border-white/10 flex items-center gap-3 flex-shrink-0" :class="sidebarOpen ? 'px-6' : 'lg:px-4 lg:justify-center px-6'">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
        <div x-show="sidebarOpen" x-transition class="overflow-hidden lg:block" :class="sidebarOpen ? '' : 'lg:hidden'">
            <h2 class="text-sm font-semibold leading-tight whitespace-nowrap">Kepegawaian TU</h2>
            <p class="text-xs text-white/60">Panel Tata Usaha</p>
        </div>
    </div>

    <nav class="flex-1 py-6 space-y-4 text-sm overflow-y-auto" :class="sidebarOpen ? 'px-4' : 'lg:px-2 px-4'">
        @if($isTuModuleUser)
            <a href="{{ route('kepegawaian-tu.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('kepegawaian-tu.dashboard') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                <x-heroicon-o-home class="w-5 h-5 flex-shrink-0"/>
                <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Dashboard</span>
            </a>
        @endif

        @if($isPrivilegedUser && $isTuModuleUser)
            <div>
                <p class="text-xs uppercase text-white/40 px-3 mb-2" x-show="sidebarOpen">Approval</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('kepegawaian-tu.pusat-approval.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('kepegawaian-tu.pusat-approval.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                            <x-heroicon-o-clipboard-document-check class="w-5 h-5 flex-shrink-0"/>
                            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Pusat Approval</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        @if($isTuModuleUser)
            <div>
                <p class="text-xs uppercase text-white/40 px-3 mb-2" x-show="sidebarOpen">Dokumen</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('kepegawaian-tu.surat.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('kepegawaian-tu.surat.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                            <x-heroicon-o-document-text class="w-5 h-5 flex-shrink-0"/>
                            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Surat Menyurat</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kepegawaian-tu.berita-acara-final.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('kepegawaian-tu.berita-acara-final.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                            <x-heroicon-o-inbox-stack class="w-5 h-5 flex-shrink-0"/>
                            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Berita Acara Final</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kepegawaian-tu.arsip-digital.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('kepegawaian-tu.arsip-digital.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                            <x-heroicon-o-archive-box class="w-5 h-5 flex-shrink-0"/>
                            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Arsip Digital</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

        <div>
            <p class="text-xs uppercase text-white/40 px-3 mb-2" x-show="sidebarOpen">SDM</p>
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('kepegawaian-tu.izin-karyawan.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('kepegawaian-tu.izin-karyawan.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                        <x-heroicon-o-document-check class="w-5 h-5 flex-shrink-0"/>
                        <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Izin Karyawan</span>
                    </a>
                </li>
            </ul>
        </div>

        @if($isPrivilegedUser && $isTuModuleUser)
            <div>
                <p class="text-xs uppercase text-white/40 px-3 mb-2" x-show="sidebarOpen">Monitoring</p>
                <ul class="space-y-1">
                    <li>
                        <a href="{{ route('kepegawaian-tu.template-surat.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('kepegawaian-tu.template-surat.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                            <x-heroicon-o-document-duplicate class="w-5 h-5 flex-shrink-0"/>
                            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Template Surat</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('kepegawaian-tu.audit-log.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-lg transition {{ request()->routeIs('kepegawaian-tu.audit-log.*') ? 'bg-white/20 text-white' : 'hover:bg-white/10' }}" :class="sidebarOpen ? '' : 'justify-center'">
                            <x-heroicon-o-clock class="w-5 h-5 flex-shrink-0"/>
                            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Audit Log</span>
                        </a>
                    </li>
                </ul>
            </div>
        @endif

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
        <span x-show="sidebarOpen">© {{ date('Y') }} Kepegawaian TU</span>
        <span x-show="!sidebarOpen">©{{ date('y') }}</span>
    </div>
</aside>
