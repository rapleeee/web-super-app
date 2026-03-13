@extends('layouts.laboran')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Unit Komputer</h1>
            <p class="mt-1 text-gray-600">Kelola unit komputer di setiap laboratorium</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('laboran.unit-komputer.import') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-green-600 px-4 py-2 text-white transition hover:bg-green-700">
                <x-heroicon-o-arrow-up-tray class="h-5 w-5" />
                Import
            </a>
            <a href="{{ route('laboran.unit-komputer.create') }}"
                class="inline-flex items-center gap-2 rounded-lg bg-[#272125] px-4 py-2 text-white transition hover:bg-[#3a3136]">
                <x-heroicon-o-plus class="h-5 w-5" />
                Tambah Unit
            </a>
        </div>
    </div>

    @if (session('import_errors'))
        <div class="rounded-lg border border-yellow-200 bg-yellow-50 p-4">
            <div class="flex items-start gap-3">
                <x-heroicon-o-exclamation-triangle class="mt-0.5 h-5 w-5 flex-shrink-0 text-yellow-600" />
                <div>
                    <h4 class="font-medium text-yellow-800">Beberapa baris gagal diimport:</h4>
                    <ul class="mt-2 list-inside list-disc text-sm text-yellow-700">
                        @foreach (session('import_errors') as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <div class="rounded-xl bg-white p-4 shadow-sm">
        <form action="{{ route('laboran.unit-komputer.index') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="min-w-[200px] flex-1">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode/nama unit..."
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-[#272125]">
            </div>
            <div class="w-48">
                <select name="laboratorium"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-[#272125]">
                    <option value="">Semua Lab</option>
                    @foreach ($laboratoriums as $lab)
                        <option value="{{ $lab->id }}" @selected(request('laboratorium') == $lab->id)>{{ $lab->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="w-44">
                <select name="kondisi"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-[#272125]">
                    <option value="">Semua Kondisi</option>
                    <option value="baik" @selected(request('kondisi') === 'baik')>Baik</option>
                    <option value="rusak_ringan" @selected(request('kondisi') === 'rusak_ringan')>Rusak Ringan</option>
                    <option value="rusak_berat" @selected(request('kondisi') === 'rusak_berat')>Rusak Berat</option>
                    <option value="mati_total" @selected(request('kondisi') === 'mati_total')>Mati Total</option>
                </select>
            </div>
            <div class="w-44">
                <select name="status"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-[#272125]">
                    <option value="">Semua Status</option>
                    <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                    <option value="dalam_perbaikan" @selected(request('status') === 'dalam_perbaikan')>Dalam Perbaikan</option>
                    <option value="tidak_aktif" @selected(request('status') === 'tidak_aktif')>Tidak Aktif</option>
                </select>
            </div>
            <div class="w-36">
                <select name="per_page" onchange="this.form.submit()"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:border-transparent focus:ring-2 focus:ring-[#272125]">
                    @foreach ($perPageOptions as $option)
                        <option value="{{ $option }}" @selected((int) request('per_page', $perPage) === $option)>{{ $option }} / halaman</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-[#272125] px-4 py-2 text-white transition hover:bg-[#3a3136]">
                    <x-heroicon-o-magnifying-glass class="h-5 w-5" />
                </button>
                @if (request()->hasAny(['search', 'laboratorium', 'kondisi', 'status', 'per_page']))
                    <a href="{{ route('laboran.unit-komputer.index') }}"
                        class="rounded-lg bg-gray-100 px-4 py-2 text-gray-700 transition hover:bg-gray-200">
                        <x-heroicon-o-x-mark class="h-5 w-5" />
                    </a>
                @endif
            </div>
        </form>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
        <div class="mb-3 flex flex-wrap items-center justify-between gap-2 text-sm">
            <p class="font-medium text-gray-700">Dipilih: <span id="selected-count">0</span> unit</p>
            <p class="text-gray-500">Bulk action berlaku untuk item yang dicentang di tabel.</p>
        </div>

        <form id="bulk-update-form" action="{{ route('laboran.unit-komputer.bulk-update') }}" method="POST" class="grid gap-3 md:grid-cols-5">
            @csrf
            @method('PATCH')
            <input type="hidden" name="mode" id="bulk-mode" value="">

            <select name="laboratorium_id" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-transparent focus:ring-2 focus:ring-[#272125]">
                <option value="">Laboratorium (opsional)</option>
                @foreach ($laboratoriums as $lab)
                    <option value="{{ $lab->id }}">{{ $lab->nama }}</option>
                @endforeach
            </select>
            <input type="number" name="nomor_meja" min="1" max="100" placeholder="Nomor meja"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-transparent focus:ring-2 focus:ring-[#272125]">
            <select name="kondisi" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-transparent focus:ring-2 focus:ring-[#272125]">
                <option value="">Kondisi (opsional)</option>
                <option value="baik">Baik</option>
                <option value="rusak_ringan">Rusak Ringan</option>
                <option value="rusak_berat">Rusak Berat</option>
                <option value="mati_total">Mati Total</option>
            </select>
            <select name="status" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-transparent focus:ring-2 focus:ring-[#272125]">
                <option value="">Status (opsional)</option>
                <option value="aktif">Aktif</option>
                <option value="dalam_perbaikan">Dalam Perbaikan</option>
                <option value="tidak_aktif">Tidak Aktif</option>
            </select>
            <input type="text" name="keterangan" maxlength="500" placeholder="Keterangan"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-transparent focus:ring-2 focus:ring-[#272125] md:col-span-5">

            <div class="flex flex-wrap gap-2 md:col-span-5">
                <button type="button" id="bulk-fill-empty-button"
                    class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-gray-300">
                    Bulk Input (Isi Kosong)
                </button>
                <button type="button" id="bulk-overwrite-button"
                    class="rounded-lg bg-amber-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-amber-700 disabled:cursor-not-allowed disabled:bg-gray-300">
                    Bulk Edit (Timpa)
                </button>
            </div>
        </form>

        <form id="bulk-delete-form" action="{{ route('laboran.unit-komputer.bulk-delete') }}" method="POST" class="mt-3">
            @csrf
            @method('DELETE')
            <button type="button" id="bulk-delete-button"
                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:bg-gray-300">
                Bulk Delete
            </button>
        </form>
    </div>

    <div class="overflow-hidden rounded-xl bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                    <tr>
                        <th class="w-12 px-4 py-4 text-center" onclick="event.stopPropagation()">
                            <input type="checkbox" id="select-all-units"
                                class="rounded border-gray-300 text-[#272125] focus:ring-[#272125]">
                        </th>
                        <th class="w-16 px-6 py-4 text-center">No</th>
                        <th class="px-6 py-4 text-left">Kode Unit</th>
                        <th class="px-6 py-4 text-left">Nama</th>
                        <th class="px-6 py-4 text-left">Laboratorium</th>
                        <th class="px-6 py-4 text-center">Meja</th>
                        <th class="px-6 py-4 text-center">Komponen</th>
                        <th class="px-6 py-4 text-center">Kondisi</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($unitKomputers as $index => $unit)
                        <tr class="group cursor-pointer hover:bg-gray-50"
                            onclick="window.location='{{ route('laboran.unit-komputer.show', $unit) }}'">
                            <td class="px-4 py-4 text-center" onclick="event.stopPropagation()">
                                <input type="checkbox" value="{{ $unit->id }}"
                                    class="unit-checkbox rounded border-gray-300 text-[#272125] focus:ring-[#272125]">
                            </td>
                            <td class="px-6 py-4 text-center text-gray-500">{{ $unitKomputers->firstItem() + $index }}</td>
                            <td class="px-6 py-4 font-medium text-gray-900 transition group-hover:text-blue-600">{{ $unit->kode_unit }}</td>
                            <td class="px-6 py-4 transition group-hover:text-blue-600">{{ $unit->nama }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $unit->laboratorium->nama }}</td>
                            <td class="px-6 py-4 text-center text-gray-600">{{ $unit->nomor_meja ?? '-' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                    {{ $unit->komponen_perangkats_count }} item
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $unit->kondisi === 'baik' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $unit->kondisi === 'rusak_ringan' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $unit->kondisi === 'rusak_berat' ? 'bg-orange-100 text-orange-800' : '' }}
                                    {{ $unit->kondisi === 'mati_total' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ str_replace('_', ' ', ucfirst($unit->kondisi)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium
                                    {{ $unit->status === 'aktif' ? 'bg-green-100 text-green-800' : '' }}
                                    {{ $unit->status === 'dalam_perbaikan' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                    {{ $unit->status === 'tidak_aktif' ? 'bg-red-100 text-red-800' : '' }}">
                                    {{ str_replace('_', ' ', ucfirst($unit->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4" onclick="event.stopPropagation()">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('laboran.unit-komputer.edit', $unit) }}" class="text-yellow-600 hover:text-yellow-800"
                                        title="Edit">
                                        <x-heroicon-o-pencil-square class="h-5 w-5" />
                                    </a>
                                    <form id="delete-unit-{{ $unit->id }}" action="{{ route('laboran.unit-komputer.destroy', $unit) }}" method="POST"
                                        class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            onclick="confirmDelete('delete-unit-{{ $unit->id }}', 'Hapus Unit?', 'Data {{ $unit->nama }} dan semua komponennya akan dihapus permanen!')"
                                            class="text-red-600 hover:text-red-800" title="Hapus">
                                            <x-heroicon-o-trash class="h-5 w-5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center text-gray-500">
                                <x-heroicon-o-computer-desktop class="mx-auto mb-3 h-12 w-12 text-gray-300" />
                                <p>Belum ada data unit komputer.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex flex-col gap-3 border-t border-gray-100 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-gray-500">
                Menampilkan {{ $unitKomputers->firstItem() ?? 0 }} - {{ $unitKomputers->lastItem() ?? 0 }} dari {{ $unitKomputers->total() }} data
            </p>
            @if ($unitKomputers->hasPages())
                <div>
                    {{ $unitKomputers->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectAllCheckbox = document.getElementById('select-all-units');
        const unitCheckboxes = Array.from(document.querySelectorAll('.unit-checkbox'));
        const selectedCount = document.getElementById('selected-count');
        const bulkFillEmptyButton = document.getElementById('bulk-fill-empty-button');
        const bulkOverwriteButton = document.getElementById('bulk-overwrite-button');
        const bulkDeleteButton = document.getElementById('bulk-delete-button');
        const bulkUpdateForm = document.getElementById('bulk-update-form');
        const bulkDeleteForm = document.getElementById('bulk-delete-form');
        const bulkModeInput = document.getElementById('bulk-mode');

        function getSelectedIds() {
            return unitCheckboxes
                .filter(function (checkbox) {
                    return checkbox.checked;
                })
                .map(function (checkbox) {
                    return checkbox.value;
                });
        }

        function appendSelectedIds(form, unitIds) {
            form.querySelectorAll('input[data-unit-id-input="true"]').forEach(function (input) {
                input.remove();
            });

            unitIds.forEach(function (id) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'unit_ids[]';
                input.value = id;
                input.dataset.unitIdInput = 'true';
                form.appendChild(input);
            });
        }

        function updateSelectState() {
            const selectedIds = getSelectedIds();
            selectedCount.textContent = selectedIds.length;

            const hasSelection = selectedIds.length > 0;
            bulkFillEmptyButton.disabled = !hasSelection;
            bulkOverwriteButton.disabled = !hasSelection;
            bulkDeleteButton.disabled = !hasSelection;

            if (selectedIds.length === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
                return;
            }

            if (selectedIds.length === unitCheckboxes.length) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
                return;
            }

            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = true;
        }

        function hasBulkFieldValue() {
            const fieldNames = ['laboratorium_id', 'nomor_meja', 'kondisi', 'status', 'keterangan'];

            return fieldNames.some(function (fieldName) {
                const field = bulkUpdateForm.querySelector('[name="' + fieldName + '"]');

                if (!field) {
                    return false;
                }

                return String(field.value || '').trim() !== '';
            });
        }

        function submitBulkUpdate(mode) {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                showToast('warning', 'Pilih minimal satu unit komputer.');
                return;
            }

            if (!hasBulkFieldValue()) {
                showToast('warning', 'Isi minimal satu field untuk bulk input/edit.');
                return;
            }

            bulkModeInput.value = mode;
            appendSelectedIds(bulkUpdateForm, selectedIds);
            bulkUpdateForm.submit();
        }

        function submitBulkDelete() {
            const selectedIds = getSelectedIds();
            if (selectedIds.length === 0) {
                showToast('warning', 'Pilih minimal satu unit komputer.');
                return;
            }

            confirmAction({
                title: 'Hapus Beberapa Unit?',
                text: selectedIds.length + ' unit komputer akan dihapus permanen.',
                icon: 'warning',
                confirmColor: '#dc2626',
                confirmText: 'Ya, Hapus',
            }).then(function (result) {
                if (result.isConfirmed) {
                    appendSelectedIds(bulkDeleteForm, selectedIds);
                    bulkDeleteForm.submit();
                }
            });
        }

        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', function () {
                unitCheckboxes.forEach(function (checkbox) {
                    checkbox.checked = selectAllCheckbox.checked;
                });

                updateSelectState();
            });
        }

        unitCheckboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', updateSelectState);
        });

        bulkFillEmptyButton.addEventListener('click', function () {
            submitBulkUpdate('fill_empty');
        });

        bulkOverwriteButton.addEventListener('click', function () {
            submitBulkUpdate('overwrite');
        });

        bulkDeleteButton.addEventListener('click', submitBulkDelete);

        updateSelectState();
    });
</script>
@endpush
