<x-app-layout>
<div class="h-[calc(100vh-theme(spacing.16))] flex flex-col overflow-hidden bg-gray-50 p-6">
    {{-- Header --}}
    <header class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Portal Sistem Terpusat</h1>
        <p class="text-gray-500 mt-1">Selamat datang kembali, {{ $user->name }}.</p>
    </header>

    {{-- Main Content --}}
    <main class="flex-1 grid grid-cols-1 lg:grid-cols-3 gap-6 min-h-0">
        {{-- Left Column: Modules --}}
        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6 content-start">
            @foreach ($menus as $menu)
                <a href="{{ $menu['route'] }}"
                   class="group relative bg-white rounded-xl border border-gray-200 p-6 transition-all duration-300
                          {{ $menu['active'] ? 'hover:border-'.$menu['color'].'-500 hover:shadow-lg' : 'opacity-60 grayscale' }}">
                    
                    @if (!$menu['active'])
                        <span class="absolute top-3 right-3 px-2 py-1 bg-gray-200 text-gray-700 text-xs font-semibold rounded-full">
                            Segera Hadir
                        </span>
                    @endif

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 flex-shrink-0 flex items-center justify-center rounded-lg bg-{{$menu['color']}}-100 text-{{$menu['color']}}-600">
                            <x-dynamic-component :component="'heroicon-o-' . $menu['icon']" class="w-6 h-6"/>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900 text-lg">{{ $menu['title'] }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $menu['desc'] }}</p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Right Column: Announcements --}}
        <aside class="bg-white rounded-xl border border-gray-200 p-6 flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Pengumuman</h2>
                <a href="#" class="text-sm text-blue-600 hover:underline">Lihat semua</a>
            </div>
            <div class="flex-1 space-y-4 overflow-y-auto -mr-2 pr-2">
                @forelse ($announcements as $announcement)
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 flex-shrink-0 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center">
                            <x-heroicon-o-megaphone class="w-5 h-5"/>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $announcement['title'] }}</p>
                            <p class="text-xs text-gray-500">
                                <span class="font-medium
                                    @if($announcement['category'] === 'Info Teknis') text-blue-600
                                    @elseif($announcement['category'] === 'Pembaruan') text-purple-600
                                    @else text-green-600 @endif
                                ">{{ $announcement['category'] }}</span>
                                • {{ \Carbon\Carbon::parse($announcement['date'])->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <x-heroicon-o-check-circle class="w-12 h-12 mx-auto text-gray-300 mb-2"/>
                        <p class="text-sm text-gray-500">Tidak ada pengumuman saat ini.</p>
                    </div>
                @endforelse
            </div>
        </aside>
    </main>
</div>
</x-app-layout>