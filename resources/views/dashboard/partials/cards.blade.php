@php
$menus = [
    [
        'title' => 'Administrasi Guru',
        'desc'  => 'Kelola data guru, jadwal mengajar, dan administrasi kepegawaian.',
        'icon'  => '
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M12 14l6.16-3.422A12.083 12.083 0 0112 20.055
                         12.083 12.083 0 015.84 10.578L12 14z"/>
            </svg>',
        'route' => '#',
    ],
    [
        'title' => 'Sarpras – Lab',
        'desc'  => 'Manajemen inventaris dan peminjaman laboratorium.',
        'icon'  => '
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477
                         a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 14.88
                         a2 2 0 00-1.806.547"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M8 7h8"/>
            </svg>',
        'route' => '#',
    ],
    [
        'title' => 'Kelola TU',
        'desc'  => 'Pengelolaan administrasi tata usaha sekolah.',
        'icon'  => '
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M7 7h10M7 11h10M7 15h6"/>
            </svg>',
        'route' => '#',
    ],
    [
        'title' => 'Sarpras – Sekolah',
        'desc'  => 'Monitoring dan pendataan sarana prasarana sekolah.',
        'icon'  => '
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                      d="M3 10l9-6 9 6v8a2 2 0 01-2 2h-4v-6H9v6H5a2 2 0 01-2-2z"/>
            </svg>',
        'route' => '#',
    ],
];
@endphp


@foreach ($menus as $menu)
<div class="group bg-white rounded-2xl border border-gray-100 shadow-sm 
            hover:shadow-xl transition-all duration-300 p-6 flex flex-col justify-between">

    {{-- ICON --}}
    <div class="flex items-center justify-between">
        <div class="w-12 h-12 flex items-center justify-center rounded-xl
                    bg-[#BFB07C]/10 text-[#BFB07C] group-hover:bg-[#BFB07C] 
                    group-hover:text-white transition">
            <x-heroicon-o-{{ $menu['icon'] }} class="w-6 h-6"/>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="mt-5">
        <h3 class="text-lg font-semibold text-gray-800 leading-tight">
            {{ $menu['title'] }}
        </h3>
        <p class="mt-2 text-sm text-gray-500 leading-relaxed">
            {{ $menu['desc'] }}
        </p>
    </div>

    {{-- ACTION --}}
    <a href="{{ $menu['route'] }}"
       class="mt-6 inline-flex items-center justify-center gap-2
              bg-[#BFB07C] text-white text-sm font-medium
              py-2.5 rounded-xl hover:bg-[#A8976A] transition">
        Mulai Kelola
        <x-heroicon-o-arrow-right class="w-4 h-4"/>
    </a>
</div>
@endforeach