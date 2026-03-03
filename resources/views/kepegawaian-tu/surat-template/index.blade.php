@extends('layouts.kepegawaian-tu')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Template Surat</h1>
            <p class="mt-1 text-gray-600">Kelola template dinamis untuk surat resmi Kepegawaian TU.</p>
        </div>
        <a href="{{ route('kepegawaian-tu.template-surat.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800 transition">
            <x-heroicon-o-plus class="w-4 h-4"/>
            Template Baru
        </a>
    </div>

    <div class="rounded-xl bg-white p-4 shadow-sm">
        <form action="{{ route('kepegawaian-tu.template-surat.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode/nama/judul..." class="rounded-lg border border-gray-300 px-4 py-2 text-sm min-w-[220px]">
            <select name="status" class="rounded-lg border border-gray-300 px-4 py-2 text-sm">
                <option value="">Semua Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit" class="rounded-lg bg-slate-700 px-4 py-2 text-white hover:bg-slate-800 transition">Filter</button>
            @if(request()->filled('search') || request()->filled('status'))
                <a href="{{ route('kepegawaian-tu.template-surat.index') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition">Reset</a>
            @endif
        </form>
    </div>

    <div class="rounded-xl bg-white shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left">Kode</th>
                        <th class="px-6 py-3 text-left">Nama</th>
                        <th class="px-6 py-3 text-left">Judul Surat</th>
                        <th class="px-6 py-3 text-center">Status</th>
                        <th class="px-6 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($templates as $template)
                        <tr>
                            <td class="px-6 py-4 text-gray-700 font-medium">{{ $template->kode }}</td>
                            <td class="px-6 py-4 text-gray-900">{{ $template->nama }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $template->judul }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $template->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $template->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('kepegawaian-tu.template-surat.edit', $template) }}" class="text-blue-600 hover:text-blue-800" title="Edit">
                                        <x-heroicon-o-pencil-square class="w-5 h-5"/>
                                    </a>
                                    <form id="delete-template-{{ $template->id }}" action="{{ route('kepegawaian-tu.template-surat.destroy', $template) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="confirmDelete('delete-template-{{ $template->id }}', 'Hapus Template?', 'Template surat akan dihapus permanen!')" class="text-red-600 hover:text-red-800" title="Hapus">
                                            <x-heroicon-o-trash class="w-5 h-5"/>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">Belum ada template surat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($templates->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">{{ $templates->links() }}</div>
        @endif
    </div>
</div>
@endsection
