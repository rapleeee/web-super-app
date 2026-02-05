@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Berita Acara</h1>
            <p class="text-gray-600 mt-1">Laporan penggunaan laboratorium</p>
        </div>
        <div class="flex items-center gap-2">
            {{-- Export Button --}}
            <div x-data="{ showExportModal: false }">
                <button @click="showExportModal = true"
                        class="inline-flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                    <x-heroicon-o-arrow-down-tray class="w-5 h-5"/>
                    Export Laporan
                </button>

                {{-- Export Modal --}}
                <div x-show="showExportModal" x-cloak
                     class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
                     @click.self="showExportModal = false">
                    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-4 p-6" @click.stop>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Export Berita Acara</h3>
                            <button @click="showExportModal = false" class="text-gray-400 hover:text-gray-600">
                                <x-heroicon-o-x-mark class="w-5 h-5"/>
                            </button>
                        </div>

                        <form action="{{ route('laboran.berita-acara.export') }}" method="GET">
                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                                        <input type="date" name="start_date" id="start_date" 
                                               value="{{ now()->startOfMonth()->format('Y-m-d') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent text-sm">
                                    </div>
                                    <div>
                                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
                                        <input type="date" name="end_date" id="end_date"
                                               value="{{ now()->format('Y-m-d') }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent text-sm">
                                    </div>
                                </div>

                                <div>
                                    <label for="laboratorium_id" class="block text-sm font-medium text-gray-700 mb-1">Laboratorium</label>
                                    <select name="laboratorium_id" id="laboratorium_id"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent text-sm">
                                        <option value="">Semua Laboratorium</option>
                                        @foreach (\App\Models\Laboratorium::orderBy('nama')->get() as $lab)
                                            <option value="{{ $lab->id }}">{{ $lab->nama }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                                    <p class="text-sm text-yellow-800">
                                        <x-heroicon-o-information-circle class="w-4 h-4 inline mr-1"/>
                                        Hanya berita acara dengan status <strong>Final</strong> yang akan diekspor.
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-3 mt-6">
                                <button type="button" @click="showExportModal = false" 
                                        class="px-4 py-2 text-gray-700 hover:text-gray-900">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                    <x-heroicon-o-arrow-down-tray class="w-4 h-4"/>
                                    Download CSV
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <a href="{{ route('laboran.berita-acara.create') }}"
               class="inline-flex items-center gap-2 bg-[#272125] text-white px-4 py-2 rounded-lg hover:bg-[#3a3136] transition">
                <x-heroicon-o-plus class="w-5 h-5"/>
                Tambah Berita Acara
            </a>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 text-left">Tanggal</th>
                        <th class="px-6 py-4 text-left">Waktu</th>
                        <th class="px-6 py-4 text-left">Laboratorium</th>
                        <th class="px-6 py-4 text-left">Guru</th>
                        <th class="px-6 py-4 text-center">Kelas</th>
                        <th class="px-6 py-4 text-center">Siswa</th>
                        <th class="px-6 py-4 text-center">PC</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($beritaAcaras as $ba)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-gray-900">
                                {{ $ba->tanggal->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($ba->waktu_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($ba->waktu_selesai)->format('H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-medium">{{ $ba->laboratorium->nama }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $ba->nama_guru }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ $ba->kelas }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center text-gray-600">{{ $ba->jumlah_siswa }}</td>
                            <td class="px-6 py-4 text-center text-gray-600">{{ $ba->jumlah_pc_digunakan }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $ba->status === 'final' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($ba->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('laboran.berita-acara.show', $ba) }}" class="text-blue-600 hover:text-blue-800" title="Lihat">
                                        <x-heroicon-o-eye class="w-5 h-5"/>
                                    </a>
                                    <a href="{{ route('laboran.berita-acara.edit', $ba) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                        <x-heroicon-o-pencil-square class="w-5 h-5"/>
                                    </a>
                                    <form id="delete-ba-{{ $ba->id }}" action="{{ route('laboran.berita-acara.destroy', $ba) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('delete-ba-{{ $ba->id }}', 'Hapus Berita Acara?', 'Data akan dihapus permanen!')" class="text-red-600 hover:text-red-800" title="Hapus">
                                            <x-heroicon-o-trash class="w-5 h-5"/>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <x-heroicon-o-document-text class="w-12 h-12 mx-auto text-gray-300 mb-3"/>
                                <p>Belum ada berita acara.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($beritaAcaras->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $beritaAcaras->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
