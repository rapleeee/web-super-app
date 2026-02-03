@extends('layouts.laboran', ['title' => 'Notifikasi'])

@section('content')
<div class="max-w-full mx-auto">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Notifikasi</h1>
            <p class="text-gray-500 text-sm mt-1">Semua notifikasi aktivitas sistem</p>
        </div>

        <div class="flex items-center gap-3">
            @if($notifications->where('read_at', null)->count() > 0)
                <form action="{{ route('laboran.notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                        Tandai Semua Dibaca
                    </button>
                </form>
            @endif

            @if($notifications->count() > 0)
                <form action="{{ route('laboran.notifications.destroy-all') }}" method="POST"
                      onsubmit="return confirm('Hapus semua notifikasi?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 font-medium">
                        Hapus Semua
                    </button>
                </form>
            @endif
        </div>
    </div>

    {{-- Notification List --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @forelse($notifications as $notification)
            <div class="flex items-start gap-4 px-6 py-4 border-b last:border-0 hover:bg-gray-50 transition {{ $notification->isUnread() ? 'bg-blue-50/30' : '' }}">
                {{-- Icon --}}
                <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center
                    @switch($notification->icon)
                        @case('wrench-screwdriver')
                            bg-blue-100 text-blue-600
                            @break
                        @case('check-circle')
                            bg-green-100 text-green-600
                            @break
                        @case('x-circle')
                            bg-red-100 text-red-600
                            @break
                        @case('arrow-path')
                            bg-yellow-100 text-yellow-600
                            @break
                        @default
                            bg-gray-100 text-gray-600
                    @endswitch
                ">
                    @switch($notification->icon)
                        @case('wrench-screwdriver')
                            <x-heroicon-o-wrench-screwdriver class="w-5 h-5"/>
                            @break
                        @case('check-circle')
                            <x-heroicon-o-check-circle class="w-5 h-5"/>
                            @break
                        @case('x-circle')
                            <x-heroicon-o-x-circle class="w-5 h-5"/>
                            @break
                        @case('arrow-path')
                            <x-heroicon-o-arrow-path class="w-5 h-5"/>
                            @break
                        @default
                            <x-heroicon-o-bell class="w-5 h-5"/>
                    @endswitch
                </div>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <p class="font-medium text-gray-900">{{ $notification->title }}</p>
                            <p class="text-sm text-gray-600 mt-0.5">{{ $notification->message }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>

                        <div class="flex items-center gap-2 flex-shrink-0">
                            @if($notification->isUnread())
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                            @endif

                            @if($notification->link)
                                <form action="{{ route('laboran.notifications.mark-as-read', $notification) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                                        Lihat
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('laboran.notifications.destroy', $notification) }}" method="POST"
                                  onsubmit="return confirm('Hapus notifikasi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 transition">
                                    <x-heroicon-o-trash class="w-4 h-4"/>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="py-16 text-center text-gray-400">
                <x-heroicon-o-bell-slash class="w-16 h-16 mx-auto mb-4 opacity-50"/>
                <p class="text-lg">Tidak ada notifikasi</p>
                <p class="text-sm mt-1">Notifikasi akan muncul saat ada aktivitas baru</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
