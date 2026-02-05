@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Data Kelas</h1>
            <p class="text-gray-600 mt-1">Kelola data kelas (tingkat, jurusan, rombel)</p>
        </div>
        <a href="{{ route('laboran.data-master.kelas.create') }}"
           class="inline-flex items-center gap-2 bg-[#272125] text-white px-4 py-2 rounded-lg hover:bg-[#3a3136] transition">
            <x-heroicon-o-plus class="w-5 h-5"/>
            Tambah Kelas
        </a>
    </div>

    {{-- Filter --}}
    <div class="bg-white rounded-xl shadow-sm p-4">
        <form action="{{ route('laboran.data-master.kelas.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div>
                <select name="tingkat" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent">
                    <option value="">Semua Tingkat</option>
                    @foreach (\App\Models\Kelas::tingkatOptions() as $tingkat)
                        <option value="{{ $tingkat }}" {{ request('tingkat') === $tingkat ? 'selected' : '' }}>Kelas {{ $tingkat }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="jurusan" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent">
                    <option value="">Semua Jurusan</option>
                    @foreach (\App\Models\Kelas::jurusanOptions() as $jurusan)
                        <option value="{{ $jurusan }}" {{ request('jurusan') === $jurusan ? 'selected' : '' }}>{{ $jurusan }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#272125] focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                <x-heroicon-o-magnifying-glass class="w-5 h-5"/>
            </button>
            @if(request()->hasAny(['tingkat', 'jurusan', 'status']))
                <a href="{{ route('laboran.data-master.kelas.index') }}" class="px-4 py-2 text-gray-600 hover:text-gray-900">
                    Reset
                </a>
            @endif
        </form>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                    <tr>
                        <th class="px-6 py-4 text-left">Kelas</th>
                        <th class="px-6 py-4 text-center">Tingkat</th>
                        <th class="px-6 py-4 text-center">Jurusan</th>
                        <th class="px-6 py-4 text-center">Rombel</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($kelass as $kelas)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600 font-bold text-sm">
                                        {{ $kelas->tingkat }}
                                    </div>
                                    <span class="font-medium">{{ $kelas->nama_lengkap }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Kelas {{ $kelas->tingkat }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $jurusanColors = [
                                        'RPL' => 'bg-blue-100 text-blue-800',
                                        'DKV' => 'bg-pink-100 text-pink-800',
                                        'TKJ' => 'bg-orange-100 text-orange-800',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $jurusanColors[$kelas->jurusan] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $kelas->jurusan }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center font-medium">{{ $kelas->rombel }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $kelas->status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($kelas->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('laboran.data-master.kelas.edit', $kelas) }}" class="text-yellow-600 hover:text-yellow-800" title="Edit">
                                        <x-heroicon-o-pencil-square class="w-5 h-5"/>
                                    </a>
                                    <form id="delete-kelas-{{ $kelas->id }}" action="{{ route('laboran.data-master.kelas.destroy', $kelas) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('delete-kelas-{{ $kelas->id }}', 'Hapus Kelas?', 'Data {{ $kelas->nama_lengkap }} akan dihapus permanen!')" class="text-red-600 hover:text-red-800" title="Hapus">
                                            <x-heroicon-o-trash class="w-5 h-5"/>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <x-heroicon-o-academic-cap class="w-12 h-12 mx-auto text-gray-300 mb-3"/>
                                <p>Belum ada data kelas.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($kelass->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $kelass->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
