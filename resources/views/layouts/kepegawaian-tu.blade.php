<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#1f2937">
    <link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/icon-192.png') }}">

    <title>Kepegawaian TU - {{ $title ?? 'Dashboard' }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">

<div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @resize.window="sidebarOpen = window.innerWidth >= 1024 ? sidebarOpen : false" class="flex h-screen bg-gray-100">
    <x-kepegawaian-tu.sidebar />

    <div class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white border-b px-6 h-16 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                    <x-heroicon-o-bars-3 class="h-6 w-6" />
                </button>
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none hidden lg:block">
                    <x-heroicon-o-bars-3 class="h-6 w-6" />
                </button>
            </div>

            <div class="flex items-center gap-4">
                @php
                    $name = Auth::user()->name;
                    $initials = collect(explode(' ', $name))
                        ->map(fn($word) => strtoupper(substr($word, 0, 1)))
                        ->take(2)
                        ->join('');
                @endphp

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="w-10 h-10 rounded-full bg-slate-700 text-white font-semibold">
                            {{ $initials }}
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                                Logout
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </header>

        <main class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </main>
    </div>
</div>

<script>
    function showToast(icon, title) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon,
            title,
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            customClass: {
                popup: 'rounded-xl'
            }
        });
    }

    function confirmDelete(formId, title = 'Hapus Data?', text = 'Data yang dihapus tidak dapat dikembalikan!') {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-xl',
                confirmButton: 'rounded-lg px-4 py-2',
                cancelButton: 'rounded-lg px-4 py-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }

    function attachUploadSizeValidation() {
        document.querySelectorAll('input[type="file"][data-max-kb]').forEach((input) => {
            input.addEventListener('change', function () {
                if (!this.files || this.files.length === 0) {
                    return;
                }

                const maxKb = Number(this.dataset.maxKb);
                const maxBytes = maxKb * 1024;
                const selectedFile = this.files[0];
                const fileLabel = this.dataset.fileLabel || 'File';

                if (Number.isNaN(maxBytes) || maxBytes <= 0) {
                    return;
                }

                if (selectedFile.size > maxBytes) {
                    this.value = '';

                    Swal.fire({
                        title: 'Ukuran File Terlalu Besar',
                        text: `${fileLabel} maksimal ${maxKb / 1024}MB. Silakan pilih file yang lebih kecil.`,
                        icon: 'error',
                        confirmButtonColor: '#dc2626',
                        customClass: {
                            popup: 'rounded-xl',
                            confirmButton: 'rounded-lg px-4 py-2'
                        }
                    });
                }
            });
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        const flashMessages = [
            { icon: 'success', message: @json(session('success')) },
            { icon: 'error', message: @json(session('error')) },
            { icon: 'warning', message: @json(session('warning')) },
            { icon: 'info', message: @json(session('info')) },
        ];

        flashMessages.forEach((flash) => {
            if (flash.message) {
                showToast(flash.icon, flash.message);
            }
        });

        const validationErrors = @json($errors->all());
        if (validationErrors.length > 0) {
            const maxVisibleErrors = 5;
            const errorItems = validationErrors
                .slice(0, maxVisibleErrors)
                .map((error) => `<li>${error}</li>`)
                .join('');

            const moreText = validationErrors.length > maxVisibleErrors
                ? `<p class="mt-2 text-sm text-gray-500">+${validationErrors.length - maxVisibleErrors} error lainnya.</p>`
                : '';

            Swal.fire({
                title: 'Validasi Gagal',
                icon: 'error',
                html: `<ul class="text-left list-disc pl-5 space-y-1">${errorItems}</ul>${moreText}`,
                confirmButtonColor: '#dc2626',
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'rounded-lg px-4 py-2'
                }
            });
        }

        attachUploadSizeValidation();
    });

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js').catch(() => {
            });
        });
    }
</script>

@stack('scripts')

</body>
</html>
