@props([
    'icon',
    'title',
    'value',
    'color' => 'gray',
])

<div class="bg-white rounded-xl border border-gray-200 p-4">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 flex-shrink-0 rounded-lg bg-{{$color}}-100 text-{{$color}}-600 flex items-center justify-center">
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5"/>
        </div>
        <div>
            <p class="text-sm text-gray-500">{{ $title }}</p>
            <p class="text-xl font-bold text-gray-900">{{ $value }}</p>
        </div>
    </div>
</div>