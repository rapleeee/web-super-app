@extends('layouts.sarpras-umum')

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Audit Log</h1>
        <p class="mt-1 text-gray-600">Riwayat perubahan data untuk kebutuhan monitoring dan jejak aktivitas.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-4">
        <form action="{{ route('sarana-umum.audit-log.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <select name="module" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm sm:w-auto">
                <option value="">Semua Modul</option>
                @foreach($modules as $module)
                    <option value="{{ $module }}" {{ request('module') === $module ? 'selected' : '' }}>{{ $module }}</option>
                @endforeach
            </select>
            <select name="action" class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm sm:w-auto">
                <option value="">Semua Aksi</option>
                @foreach($actions as $action)
                    <option value="{{ $action }}" {{ request('action') === $action ? 'selected' : '' }}>{{ $action }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-lg bg-[#272125] px-4 py-2 text-white hover:bg-[#3a3136] transition">Filter</button>
            @if(request()->filled('module') || request()->filled('action'))
                <a href="{{ route('sarana-umum.audit-log.index') }}" class="rounded-lg bg-gray-100 px-4 py-2 text-gray-700 hover:bg-gray-200 transition">Reset</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left">Waktu</th>
                        <th class="px-6 py-3 text-left">User</th>
                        <th class="px-6 py-3 text-left">Modul</th>
                        <th class="px-6 py-3 text-left">Aksi</th>
                        <th class="px-6 py-3 text-left">Objek</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($auditLogs as $log)
                        <tr>
                            <td class="px-6 py-4 text-gray-700">{{ $log->created_at?->format('d M Y H:i:s') }}</td>
                            <td class="px-6 py-4 text-gray-700">{{ $log->user?->name ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700">{{ $log->module }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-700">{{ $log->action }}</span>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-600">
                                <p>{{ class_basename((string) $log->auditable_type) ?: '-' }}</p>
                                <p>ID: {{ $log->auditable_id ?? '-' }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500">Belum ada log aktivitas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($auditLogs->hasPages())
            <div class="border-t border-gray-100 px-6 py-4">{{ $auditLogs->links() }}</div>
        @endif
    </div>
</div>
@endsection
