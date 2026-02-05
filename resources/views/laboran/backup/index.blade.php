@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Backup Database</h1>
            <p class="text-gray-600 mt-1">Kelola backup data sistem untuk keamanan</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
            <x-heroicon-o-check-circle class="w-5 h-5 text-green-500 flex-shrink-0"/>
            <span class="text-green-700">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 bg-red-50 border border-red-200 rounded-lg flex items-center gap-3">
            <x-heroicon-o-x-circle class="w-5 h-5 text-red-500 flex-shrink-0"/>
            <span class="text-red-700">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Backup Actions -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Quick Actions Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <x-heroicon-o-bolt class="w-5 h-5 text-yellow-500"/>
                    Aksi Cepat
                </h3>
                
                <div class="space-y-3">
                    <!-- Download Backup -->
                    <a href="{{ route('laboran.backup.download') }}" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <x-heroicon-o-arrow-down-tray class="w-5 h-5"/>
                        Download Backup
                    </a>

                    <!-- Save to Server -->
                    <form action="{{ route('laboran.backup.store') }}" method="POST">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                            <x-heroicon-o-server class="w-5 h-5"/>
                            Simpan ke Server
                        </button>
                    </form>
                </div>

                <div class="mt-4 p-3 bg-blue-50 rounded-lg">
                    <p class="text-xs text-blue-700">
                        <strong>Download:</strong> Langsung download file .sql ke komputer Anda.<br>
                        <strong>Simpan ke Server:</strong> Menyimpan backup di server untuk diakses nanti.
                    </p>
                </div>
            </div>

            <!-- Database Info Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <x-heroicon-o-circle-stack class="w-5 h-5 text-purple-500"/>
                    Informasi Database
                </h3>

                <div class="space-y-3">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Jumlah Tabel</span>
                        <span class="text-sm font-semibold text-gray-800">{{ count($tables) }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm text-gray-600">Database</span>
                        <span class="text-sm font-semibold text-gray-800">{{ config('database.connections.mysql.database') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <span class="text-sm text-gray-600">Driver</span>
                        <span class="text-sm font-semibold text-gray-800">{{ config('database.default') }}</span>
                    </div>
                </div>

                <!-- Tables List -->
                <div class="mt-4" x-data="{ open: false }">
                    <button type="button" 
                            @click="open = !open"
                            class="w-full text-left">
                        <div class="flex items-center justify-between text-sm text-gray-600 hover:text-gray-800">
                            <span>Lihat daftar tabel</span>
                            <x-heroicon-o-chevron-down class="w-4 h-4" x-show="!open"/>
                            <x-heroicon-o-chevron-up class="w-4 h-4" x-show="open" x-cloak/>
                        </div>
                    </button>
                    <div x-show="open" x-collapse x-cloak class="mt-2 max-h-48 overflow-y-auto">
                        <ul class="text-xs space-y-1">
                            @foreach($tables as $table)
                                <li class="py-1 px-2 bg-gray-50 rounded">{{ $table }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border border-amber-200 p-6">
                <h3 class="text-lg font-semibold text-amber-800 mb-3 flex items-center gap-2">
                    <x-heroicon-o-light-bulb class="w-5 h-5"/>
                    Tips Backup
                </h3>
                <ul class="text-sm text-amber-700 space-y-2">
                    <li class="flex items-start gap-2">
                        <x-heroicon-o-check class="w-4 h-4 mt-0.5 flex-shrink-0"/>
                        <span>Lakukan backup secara rutin, minimal seminggu sekali</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <x-heroicon-o-check class="w-4 h-4 mt-0.5 flex-shrink-0"/>
                        <span>Simpan backup di lokasi yang berbeda (cloud, external drive)</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <x-heroicon-o-check class="w-4 h-4 mt-0.5 flex-shrink-0"/>
                        <span>Backup sebelum melakukan maintenance atau update sistem</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <x-heroicon-o-check class="w-4 h-4 mt-0.5 flex-shrink-0"/>
                        <span>Verifikasi backup dengan mengimport ke database test</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Existing Backups -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 flex items-center gap-2">
                        <x-heroicon-o-folder-open class="w-5 h-5 text-gray-500"/>
                        Backup Tersimpan di Server
                    </h3>
                </div>

                @if(count($existingBackups) > 0)
                    <div class="divide-y divide-gray-100">
                        @foreach($existingBackups as $backup)
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <x-heroicon-o-document-text class="w-5 h-5 text-blue-600"/>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $backup['name'] }}</p>
                                        <p class="text-sm text-gray-500">
                                            {{ $backup['size'] }} • {{ \Carbon\Carbon::parse($backup['date'])->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('laboran.backup.download-file', $backup['name']) }}" 
                                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                       title="Download">
                                        <x-heroicon-o-arrow-down-tray class="w-5 h-5"/>
                                    </a>
                                    <form action="{{ route('laboran.backup.destroy', $backup['name']) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Hapus backup ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                title="Hapus">
                                            <x-heroicon-o-trash class="w-5 h-5"/>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="px-6 py-12 text-center">
                        <x-heroicon-o-inbox class="w-12 h-12 text-gray-300 mx-auto mb-3"/>
                        <p class="text-gray-500">Belum ada backup tersimpan di server</p>
                        <p class="text-sm text-gray-400 mt-1">Klik "Simpan ke Server" untuk membuat backup</p>
                    </div>
                @endif
            </div>

            <!-- Restore Instructions -->
            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                    <x-heroicon-o-arrow-path class="w-5 h-5 text-green-500"/>
                    Cara Restore Database
                </h3>

                <div class="space-y-4 text-sm text-gray-600">
                    <div class="flex gap-3">
                        <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">1</span>
                        <p>Download file backup .sql yang ingin di-restore</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">2</span>
                        <p>Buka phpMyAdmin atau tool database management lainnya</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">3</span>
                        <p>Pilih database yang akan di-restore, lalu klik tab "Import"</p>
                    </div>
                    <div class="flex gap-3">
                        <span class="w-6 h-6 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0">4</span>
                        <p>Upload file .sql dan klik "Go" untuk memulai proses restore</p>
                    </div>
                </div>

                <div class="mt-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-xs text-red-700">
                        <strong>Peringatan:</strong> Proses restore akan menimpa semua data yang ada. 
                        Pastikan Anda sudah backup data terbaru sebelum melakukan restore.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
