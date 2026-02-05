<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Panel Laboran - {{ $title ?? 'Dashboard' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 font-sans antialiased">

{{-- Toast Notification Component --}}
<div x-data="toastNotification()" x-init="init()" class="fixed top-4 right-4 z-[100] space-y-2">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.show"
             x-transition:enter="transform ease-out duration-300 transition"
             x-transition:enter-start="translate-x-full opacity-0"
             x-transition:enter-end="translate-x-0 opacity-100"
             x-transition:leave="transform ease-in duration-200 transition"
             x-transition:leave-start="translate-x-0 opacity-100"
             x-transition:leave-end="translate-x-full opacity-0"
             class="flex items-center gap-3 px-4 py-3 rounded-lg shadow-lg min-w-[320px]"
             :class="{
                'bg-green-500 text-white': toast.type === 'success',
                'bg-red-500 text-white': toast.type === 'error',
                'bg-yellow-500 text-white': toast.type === 'warning',
                'bg-blue-500 text-white': toast.type === 'info'
             }">
            <template x-if="toast.type === 'success'">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </template>
            <template x-if="toast.type === 'error'">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </template>
            <template x-if="toast.type === 'warning'">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </template>
            <template x-if="toast.type === 'info'">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </template>
            <span class="flex-1 text-sm font-medium" x-text="toast.message"></span>
            <button @click="removeToast(toast.id)" class="flex-shrink-0 hover:opacity-75">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </template>
</div>

<div x-data="{ sidebarOpen: window.innerWidth >= 1024 }" @resize.window="sidebarOpen = window.innerWidth >= 1024 ? sidebarOpen : false" class="flex h-screen bg-gray-100">

    <!-- SIDEBAR -->
    <x-laboran.sidebar />

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- TOP BAR -->
        <header class="bg-white border-b px-6 h-16 flex items-center justify-between flex-shrink-0">
            <!-- Mobile Toggle & Desktop Toggle -->
            <div class="flex items-center">
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none lg:hidden">
                    <x-heroicon-o-bars-3 class="h-6 w-6" />
                </button>
                <button @click="sidebarOpen = !sidebarOpen" class="text-gray-500 focus:outline-none hidden lg:block">
                    <x-heroicon-o-bars-3 class="h-6 w-6" />
                </button>
            </div>

            <!-- USER ACTION -->
            <div class="flex items-center gap-4">
                <!-- NOTIFICATION DROPDOWN -->
                <div x-data="notificationDropdown()" x-init="init()" class="relative">
                    <button @click="toggleDropdown()"
                            class="relative w-10 h-10 flex items-center justify-center rounded-full border text-gray-600 hover:bg-gray-100 transition">
                        <x-heroicon-o-bell class="w-5 h-5"/>
                        <!-- Badge -->
                        <span x-show="unreadCount > 0"
                              x-text="unreadCount > 9 ? '9+' : unreadCount"
                              class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center">
                        </span>
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="isOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.outside="isOpen = false"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-lg ring-1 ring-black/5 z-50 overflow-hidden">

                        <!-- Header -->
                        <div class="px-4 py-3 bg-gray-50 border-b flex items-center justify-between">
                            <h3 class="font-semibold text-gray-800">Notifikasi</h3>
                            <button x-show="unreadCount > 0"
                                    @click="markAllRead()"
                                    class="text-xs text-blue-600 hover:text-blue-800">
                                Tandai Semua Dibaca
                            </button>
                        </div>

                        <!-- Notification List -->
                        <div class="max-h-80 overflow-y-auto">
                            <template x-if="loading">
                                <div class="p-8 text-center text-gray-400">
                                    <svg class="animate-spin h-6 w-6 mx-auto mb-2" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Memuat...
                                </div>
                            </template>

                            <template x-if="!loading && notifications.length === 0">
                                <div class="p-8 text-center text-gray-400">
                                    <x-heroicon-o-bell-slash class="w-12 h-12 mx-auto mb-2 opacity-50"/>
                                    <p>Tidak ada notifikasi</p>
                                </div>
                            </template>

                            <template x-for="notif in notifications" :key="notif.id">
                                <a :href="'/laboran/notifications/' + notif.id + '/read'"
                                   @click.prevent="goToNotification(notif)"
                                   class="block px-4 py-3 hover:bg-gray-50 border-b last:border-0 transition"
                                   :class="notif.is_unread ? 'bg-blue-50/50' : ''">
                                    <div class="flex gap-3">
                                        <!-- Icon -->
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center"
                                             :class="{
                                                'bg-blue-100 text-blue-600': notif.icon === 'wrench-screwdriver',
                                                'bg-green-100 text-green-600': notif.icon === 'check-circle',
                                                'bg-red-100 text-red-600': notif.icon === 'x-circle',
                                                'bg-yellow-100 text-yellow-600': notif.icon === 'arrow-path',
                                                'bg-gray-100 text-gray-600': !['wrench-screwdriver', 'check-circle', 'x-circle', 'arrow-path'].includes(notif.icon)
                                             }">
                                            <template x-if="notif.icon === 'wrench-screwdriver'">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/>
                                                </svg>
                                            </template>
                                            <template x-if="notif.icon === 'check-circle'">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </template>
                                            <template x-if="notif.icon === 'x-circle'">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            </template>
                                            <template x-if="notif.icon === 'arrow-path'">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                                </svg>
                                            </template>
                                            <template x-if="!['wrench-screwdriver', 'check-circle', 'x-circle', 'arrow-path'].includes(notif.icon)">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                                </svg>
                                            </template>
                                        </div>
                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900" x-text="notif.title"></p>
                                            <p class="text-sm text-gray-500 truncate" x-text="notif.message"></p>
                                            <p class="text-xs text-gray-400 mt-1" x-text="notif.time_ago"></p>
                                        </div>
                                        <!-- Unread indicator -->
                                        <div x-show="notif.is_unread" class="flex-shrink-0">
                                            <span class="w-2 h-2 bg-blue-500 rounded-full block"></span>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </div>

                        <!-- Footer -->
                        <div class="px-4 py-3 bg-gray-50 border-t text-center">
                            <a href="{{ route('laboran.notifications.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Lihat Semua Notifikasi
                            </a>
                        </div>
                    </div>
                </div>

                <!-- USER DROPDOWN -->
                @php
                    $name = Auth::user()->name;
                    $initials = collect(explode(' ', $name))
                        ->map(fn($w) => strtoupper(substr($w,0,1)))
                        ->take(2)
                        ->join('');
                @endphp

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="w-10 h-10 rounded-full bg-[#BFB07C] text-[#272125] font-semibold">
                            {{ $initials }}
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link
                                :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Logout
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="flex-1 overflow-y-auto p-8">
            @yield('content')
        </main>
    </div>
</div>

{{-- Alpine.js Toast & Dialog Scripts --}}
<script>
    // Notification Dropdown
    function notificationDropdown() {
        return {
            isOpen: false,
            loading: false,
            notifications: [],
            unreadCount: 0,
            pollingInterval: null,

            init() {
                this.fetchUnreadCount();
                
                // Only poll when page is visible, every 60 seconds
                this.startPolling();
                
                // Pause polling when tab is hidden
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) {
                        this.stopPolling();
                    } else {
                        this.fetchUnreadCount();
                        this.startPolling();
                    }
                });
            },

            startPolling() {
                this.stopPolling(); // Clear existing interval
                this.pollingInterval = setInterval(() => {
                    if (!document.hidden) {
                        this.fetchUnreadCount();
                    }
                }, 60000); // Poll every 60 seconds (reduced from 30)
            },

            stopPolling() {
                if (this.pollingInterval) {
                    clearInterval(this.pollingInterval);
                    this.pollingInterval = null;
                }
            },

            toggleDropdown() {
                this.isOpen = !this.isOpen;
                if (this.isOpen) {
                    this.fetchNotifications();
                }
            },

            async fetchNotifications() {
                this.loading = true;
                try {
                    const response = await fetch('{{ route('laboran.notifications.recent') }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await response.json();
                    this.notifications = data.notifications;
                    this.unreadCount = data.unread_count;
                } catch (error) {
                    console.error('Failed to fetch notifications:', error);
                }
                this.loading = false;
            },

            async fetchUnreadCount() {
                try {
                    const response = await fetch('{{ route('laboran.notifications.unread-count') }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    const data = await response.json();
                    this.unreadCount = data.count;
                } catch (error) {
                    console.error('Failed to fetch unread count:', error);
                }
            },

            async markAllRead() {
                try {
                    await fetch('{{ route('laboran.notifications.mark-all-read') }}', {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    });
                    this.notifications = this.notifications.map(n => ({ ...n, is_unread: false }));
                    this.unreadCount = 0;
                } catch (error) {
                    console.error('Failed to mark all as read:', error);
                }
            },

            goToNotification(notif) {
                // Submit form to mark as read and redirect
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/laboran/notifications/' + notif.id + '/read';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
                form.appendChild(csrfInput);

                document.body.appendChild(form);
                form.submit();
            }
        }
    }

    // Toast Notification System
    function toastNotification() {
        return {
            toasts: [],
            init() {
                // Check for session flash messages
                @if(session('success'))
                    this.addToast('success', '{{ session('success') }}');
                @endif
                @if(session('error'))
                    this.addToast('error', '{{ session('error') }}');
                @endif
                @if(session('warning'))
                    this.addToast('warning', '{{ session('warning') }}');
                @endif
                @if(session('info'))
                    this.addToast('info', '{{ session('info') }}');
                @endif
            },
            addToast(type, message) {
                const id = Date.now();
                this.toasts.push({ id, type, message, show: true });
                setTimeout(() => this.removeToast(id), 4000);
            },
            removeToast(id) {
                const index = this.toasts.findIndex(t => t.id === id);
                if (index > -1) {
                    this.toasts[index].show = false;
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 200);
                }
            }
        }
    }

    // SweetAlert2 Confirm Delete
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

    // SweetAlert2 Custom Confirm
    function confirmAction(options) {
        return Swal.fire({
            title: options.title || 'Konfirmasi',
            text: options.text || 'Apakah Anda yakin?',
            icon: options.icon || 'question',
            showCancelButton: true,
            confirmButtonColor: options.confirmColor || '#272125',
            cancelButtonColor: '#6b7280',
            confirmButtonText: options.confirmText || 'Ya',
            cancelButtonText: options.cancelText || 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-xl',
                confirmButton: 'rounded-lg px-4 py-2',
                cancelButton: 'rounded-lg px-4 py-2'
            }
        });
    }

    // SweetAlert2 Success
    function showSuccess(title, text) {
        Swal.fire({
            title: title,
            text: text,
            icon: 'success',
            confirmButtonColor: '#272125',
            customClass: {
                popup: 'rounded-xl',
                confirmButton: 'rounded-lg px-4 py-2'
            }
        });
    }

    // SweetAlert2 Error
    function showError(title, text) {
        Swal.fire({
            title: title,
            text: text,
            icon: 'error',
            confirmButtonColor: '#dc2626',
            customClass: {
                popup: 'rounded-xl',
                confirmButton: 'rounded-lg px-4 py-2'
            }
        });
    }
</script>

@stack('scripts')

</body>
</html>