@props([
    'icon',
    'title',
    'desc',
    'route',
])

<div {{ $attributes->merge([
    'class' => 'group bg-white rounded-2xl border border-gray-100 
               shadow-sm duration-300 
               p-6 flex flex-col justify-between'
]) }}>

    {{-- ICON --}}
    <div class="w-12 h-12 flex items-center justify-center rounded-xl
                bg-[#BFB07C]/10 text-[#BFB07C]
                group-hover:bg-[#BFB07C] group-hover:text-white
                transition">
        <x-dynamic-component 
            :component="'heroicon-o-' . $icon" 
            class="w-6 h-6" 
        />
    </div>

    {{-- CONTENT --}}
    <div class="mt-5">
        <h3 class="text-lg font-semibold text-gray-800 leading-tight">
            {{ $title }}
        </h3>
        <p class="mt-2 text-sm text-gray-500 leading-relaxed">
            {{ $desc }}
        </p>
    </div>

    {{-- ACTION --}}
    <a href="{{ $route }}"
       class="mt-6 inline-flex items-center justify-center gap-2
              bg-[#BFB07C] text-white text-sm font-medium
              py-2.5 rounded-xl
              hover:bg-[#A8976A] transition">
        Mulai Kelola
        <x-heroicon-o-arrow-right class="w-4 h-4"/>
    </a>

</div>