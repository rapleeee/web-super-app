<script>
function updateTime() {
    const now = new Date();
    document.getElementById('time').innerText =
        now.toLocaleTimeString('id-ID');
    document.getElementById('date').innerText =
        now.toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });
}
setInterval(updateTime, 1000);
updateTime();
</script>

@if($currentWeather)
<div class="bg-gradient-to-br from-[#BFB07C] to-[#272125]
            text-white rounded-2xl shadow-xl
            p-6 h-full flex flex-col justify-between">

    {{-- HEADER --}}
    <div class="flex justify-between items-center">
        <div>
            <p class="text-sm opacity-80">Hari Ini</p>
            <p id="date" class="text-sm font-medium"></p>
        </div>
        <div class="text-sm opacity-80">
            <span id="time"></span>
        </div>
    </div>

    {{-- WEATHER MAIN --}}
    <div class="mt-6 flex items-center justify-between">
        <div>
            <h2 class="text-5xl font-bold leading-none">
                {{ $currentWeather['t'] }}°
            </h2>
            <p class="mt-2 text-sm capitalize opacity-90">
                {{ $currentWeather['weather_desc'] }}
            </p>
            <p class="text-xs opacity-75 mt-1">
                📍 {{ $weather['lokasi']['kecamatan'] ?? 'Lokasi tidak diketahui' }}
            </p>
        </div>

        {{-- ICON CUACA --}}
        <div class="w-20 h-20 rounded-full bg-white/20
                    flex items-center justify-center">
            @if(Str::contains(strtolower($currentWeather['weather_desc']), 'cerah'))
                <x-heroicon-o-sun class="w-10 h-10 text-yellow-300"/>
            @else
                <x-heroicon-o-cloud class="w-10 h-10 text-white"/>
            @endif
        </div>
    </div>

    {{-- STATS --}}
    <div class="mt-6 bg-white/15 rounded-xl p-4 grid grid-cols-3 gap-3">

        {{-- HUMIDITY --}}
        <div class="flex flex-col items-center text-center">
            <div class="w-10 h-10 rounded-full bg-white/20
                        flex items-center justify-center mb-2">
                <x-heroicon-o-beaker class="w-5 h-5"/>
            </div>
            <p class="text-xs opacity-70">Kelembapan</p>
            <p class="font-semibold">{{ $currentWeather['hu'] }}%</p>
        </div>

        {{-- WIND --}}
        <div class="flex flex-col items-center text-center">
            <div class="w-10 h-10 rounded-full bg-white/20
                        flex items-center justify-center mb-2">
                <x-heroicon-o-fire class="w-5 h-5"/>
            </div>
            <p class="text-xs opacity-70">Angin</p>
            <p class="font-semibold">{{ $currentWeather['ws'] }} km/j</p>
        </div>

        {{-- DIRECTION --}}
        <div class="flex flex-col items-center text-center">
            <div class="w-10 h-10 rounded-full bg-white/20
                        flex items-center justify-center mb-2">
                <x-heroicon-o-fire class="w-5 h-5"/>
            </div>
            <p class="text-xs opacity-70">Arah</p>
            <p class="font-semibold">{{ $currentWeather['wd'] }}</p>
        </div>

    </div>

</div>
@else
<div class="bg-white rounded-xl p-6 text-center text-gray-500">
    Data cuaca tidak tersedia.
</div>
@endif